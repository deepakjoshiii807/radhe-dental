<?php

declare(strict_types=1);

namespace App\Services\Firebase;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

final class FirestoreRepository
{
    private const DEFAULT_COLLECTIONS = [
        'services' => 'clinic.services',
        'doctors' => 'clinic.doctors',
        'testimonials' => 'clinic.testimonials',
        'gallery' => 'clinic.gallery',
        'availability' => null,
        'appointments' => null,
        'admins' => null,
        'settings' => null,
    ];

    private ?array $cachedToken = null;

    public function __construct(private readonly FirebaseFactory $factory)
    {
    }

    public function all(string $collection, array $filters = [], ?int $limit = null, bool $latestFirst = true): array
    {
        $documents = $this->database() ? $this->fetchDocuments($collection) : $this->fallbackCollection($collection);

        if ($filters !== []) {
            $documents = array_values(array_filter($documents, fn (array $document): bool => $this->matchesFilters($document, $filters)));
        }

        usort($documents, static function (array $left, array $right) use ($latestFirst): int {
            $leftDate = Arr::get($left, 'updated_at') ?? Arr::get($left, 'created_at') ?? '';
            $rightDate = Arr::get($right, 'updated_at') ?? Arr::get($right, 'created_at') ?? '';

            return $latestFirst ? strcmp((string) $rightDate, (string) $leftDate) : strcmp((string) $leftDate, (string) $rightDate);
        });

        if ($limit !== null) {
            $documents = array_slice($documents, 0, $limit);
        }

        return $documents;
    }

    public function find(string $collection, string $id): ?array
    {
        if ($this->database() === null) {
            foreach ($this->fallbackCollection($collection) as $document) {
                if (($document['id'] ?? null) === $id) {
                    return $document;
                }
            }

            return null;
        }

        try {
            $response = $this->request()->get($this->documentUrl($collection, $id));
        } catch (Throwable) {
            return null;
        }

        if ($response->status() === 404) {
            return null;
        }

        if (! $response->successful()) {
            throw new RuntimeException('Unable to fetch Firestore document: '.$response->body());
        }

        return $this->decodeDocument($response->json());
    }

    public function create(string $collection, array $data, ?string $id = null): string
    {
        if ($this->database() === null) {
            $documents = $this->fallbackCollection($collection);
            $documentId = $id ?? (string) Str::uuid();
            $documents[] = array_merge(['id' => $documentId], $this->withTimestamps($data, true));
            $this->storeFallbackCollection($collection, $documents);

            return $documentId;
        }

        $documentId = $id ?? (string) Str::uuid();
        $payload = ['fields' => $this->encodeFields($this->withTimestamps($data, true))];

        $response = $this->request()->patch($this->documentUrl($collection, $documentId).'?currentDocument.exists=false', $payload);

        if (! $response->successful()) {
            throw new RuntimeException('Unable to create Firestore document: '.$response->body());
        }

        return $documentId;
    }

    public function update(string $collection, string $id, array $data): void
    {
        if ($this->database() === null) {
            $documents = $this->fallbackCollection($collection);
            foreach ($documents as &$document) {
                if (($document['id'] ?? null) === $id) {
                    $document = array_merge($document, $this->withTimestamps($data, false), ['id' => $id]);
                    $this->storeFallbackCollection($collection, $documents);

                    return;
                }
            }

            return;
        }

        $payload = ['fields' => $this->encodeFields($this->withTimestamps($data, false))];
        $response = $this->request()->patch($this->documentUrl($collection, $id), $payload);

        if (! $response->successful()) {
            throw new RuntimeException('Unable to update Firestore document: '.$response->body());
        }
    }

    public function delete(string $collection, string $id): void
    {
        if ($this->database() === null) {
            $documents = array_values(array_filter($this->fallbackCollection($collection), static fn (array $document): bool => ($document['id'] ?? null) !== $id));
            $this->storeFallbackCollection($collection, $documents);

            return;
        }

        $response = $this->request()->delete($this->documentUrl($collection, $id));

        if (! $response->successful() && $response->status() !== 404) {
            throw new RuntimeException('Unable to delete Firestore document: '.$response->body());
        }
    }

    public function count(string $collection, array $filters = []): int
    {
        return count($this->all($collection, $filters, null, true));
    }

    private function fetchDocuments(string $collection): array
    {
        $response = $this->request()->get($this->collectionUrl($collection));

        if (! $response->successful()) {
            throw new RuntimeException('Unable to list Firestore documents: '.$response->body());
        }

        $documents = [];
        foreach (($response->json('documents') ?? []) as $document) {
            $documents[] = $this->decodeDocument($document);
        }

        return $documents;
    }

    private function request()
    {
        $caBundle = config('firebase.ca_bundle');
        $request = Http::asJson()->withToken($this->accessToken())->withOptions(['timeout' => 8]);

        if (is_string($caBundle) && $caBundle !== '' && is_file($caBundle)) {
            $request = $request->withOptions(['verify' => $caBundle, 'timeout' => 8]);
        }

        return $request;
    }

    private function accessToken(): string
    {
        // Try cache first (shared across requests/processes)
        $cacheKey = config('firebase.token_cache_key', 'firebase_access_token');
        $cached = Cache::get($cacheKey);
        if (is_array($cached) && isset($cached['access_token'], $cached['expires_at']) && $cached['expires_at'] > time() + 60) {
            return $cached['access_token'];
        }

        $serviceAccount = config('firebase.service_account');
        if (! is_array($serviceAccount) || $serviceAccount === []) {
            throw new RuntimeException('Firebase service account is not configured.');
        }

        $privateKey = $serviceAccount['private_key'] ?? null;
        $clientEmail = $serviceAccount['client_email'] ?? null;
        $projectId = $serviceAccount['project_id'] ?? config('firebase.project_id');

        if (! is_string($privateKey) || $privateKey === '' || ! is_string($clientEmail) || $clientEmail === '' || ! is_string($projectId) || $projectId === '') {
            throw new RuntimeException('Firebase service account is incomplete.');
        }

        $now = time();
        $header = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT'], JSON_THROW_ON_ERROR));
        $claims = $this->base64UrlEncode(json_encode([
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/datastore https://www.googleapis.com/auth/cloud-platform',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
        ], JSON_THROW_ON_ERROR));

        $signature = '';
        $success = openssl_sign($header.'.'.$claims, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        if (! $success) {
            throw new RuntimeException('Unable to sign Firebase service account JWT.');
        }

        $assertion = $header.'.'.$claims.'.'.$this->base64UrlEncode($signature);
        $caBundle = config('firebase.ca_bundle');
        $request = Http::asForm()->withOptions(['timeout' => 8]);

        if (is_string($caBundle) && $caBundle !== '' && is_file($caBundle)) {
            $request = $request->withOptions(['verify' => $caBundle, 'timeout' => 8]);
        }

        $response = $request->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $assertion,
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Unable to obtain Firebase access token: '.$response->body());
        }

        $access = (string) $response->json('access_token');
        $expires = $now + (int) $response->json('expires_in', 3600);

        $this->cachedToken = ['access_token' => $access, 'expires_at' => $expires];

        // store in cache a little shorter than real expiry
        $ttl = max(60, ($expires - $now) - 60);
        Cache::put($cacheKey, $this->cachedToken, $ttl);

        return $access;
    }

    private function collectionUrl(string $collection): string
    {
        return sprintf(
            'https://firestore.googleapis.com/v1/projects/%s/databases/(default)/documents/%s',
            config('firebase.project_id'),
            $collection
        );
    }

    private function documentUrl(string $collection, string $id): string
    {
        return $this->collectionUrl($collection).'/'.$id;
    }

    private function decodeDocument(array $document): array
    {
        $fields = $document['fields'] ?? [];
        $decoded = ['id' => basename((string) ($document['name'] ?? ''))];

        foreach ($fields as $key => $value) {
            $decoded[$key] = $this->decodeValue($value);
        }

        return $decoded;
    }

    private function decodeValue(array $value): mixed
    {
        if (array_key_exists('stringValue', $value)) {
            return $value['stringValue'];
        }

        if (array_key_exists('integerValue', $value)) {
            return (int) $value['integerValue'];
        }

        if (array_key_exists('doubleValue', $value)) {
            return (float) $value['doubleValue'];
        }

        if (array_key_exists('booleanValue', $value)) {
            return (bool) $value['booleanValue'];
        }

        if (array_key_exists('timestampValue', $value)) {
            return $value['timestampValue'];
        }

        if (array_key_exists('arrayValue', $value)) {
            $items = $value['arrayValue']['values'] ?? [];

            return array_map(fn (array $item): mixed => $this->decodeValue($item), $items);
        }

        if (array_key_exists('mapValue', $value)) {
            $fields = $value['mapValue']['fields'] ?? [];
            $decoded = [];
            foreach ($fields as $key => $fieldValue) {
                $decoded[$key] = $this->decodeValue($fieldValue);
            }

            return $decoded;
        }

        return null;
    }

    private function encodeFields(array $data): array
    {
        $fields = [];

        foreach ($data as $key => $value) {
            $fields[$key] = $this->encodeValue($value);
        }

        return $fields;
    }

    private function encodeValue(mixed $value): array
    {
        if ($value === null) {
            return ['nullValue' => null];
        }

        if (is_bool($value)) {
            return ['booleanValue' => $value];
        }

        if (is_int($value)) {
            return ['integerValue' => (string) $value];
        }

        if (is_float($value)) {
            return ['doubleValue' => $value];
        }

        if ($value instanceof \DateTimeInterface) {
            return ['timestampValue' => $value->format(\DateTimeInterface::ATOM)];
        }

        if (is_array($value)) {
            if ($value === []) {
                return ['arrayValue' => ['values' => []]];
            }

            if (array_is_list($value)) {
                return ['arrayValue' => ['values' => array_map(fn (mixed $item): array => $this->encodeValue($item), $value)]];
            }

            $fields = [];
            foreach ($value as $key => $item) {
                $fields[$key] = $this->encodeValue($item);
            }

            return ['mapValue' => ['fields' => $fields]];
        }

        return ['stringValue' => (string) $value];
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function withTimestamps(array $data, bool $creating): array
    {
        $now = Carbon::now()->toDateTimeString();

        return array_merge($data, [
            'created_at' => $creating ? ($data['created_at'] ?? $now) : ($data['created_at'] ?? $now),
            'updated_at' => $now,
        ]);
    }

    private function matchesFilters(array $data, array $filters): bool
    {
        foreach ($filters as $field => $expected) {
            if (! array_key_exists($field, $data)) {
                return false;
            }

            if (is_array($expected)) {
                if (! in_array($data[$field], $expected, true)) {
                    return false;
                }

                continue;
            }

            if ((string) $data[$field] !== (string) $expected) {
                return false;
            }
        }

        return true;
    }

    private function fallbackCollection(string $collection): array
    {
        $path = storage_path('app/firebase-fallback/'.$collection.'.json');

        if (is_file($path)) {
            $contents = file_get_contents($path);
            $decoded = is_string($contents) ? json_decode($contents, true) : [];

            if (is_array($decoded)) {
                return $decoded;
            }
        }

        $defaultKey = self::DEFAULT_COLLECTIONS[$collection] ?? null;
        $defaults = $defaultKey ? config($defaultKey, []) : [];

        return array_map(function (array $item, int $index) use ($collection): array {
            $id = $item['id'] ?? ($item['slug'] ?? ($item['doctor_id'] ?? ($collection.'-'.$index)));

            return array_merge(['id' => (string) $id], $item);
        }, $defaults, array_keys($defaults));
    }

    private function storeFallbackCollection(string $collection, array $documents): void
    {
        $path = storage_path('app/firebase-fallback');

        if (! is_dir($path)) {
            mkdir($path, 0777, true);
        }

        file_put_contents($path.'/'.$collection.'.json', json_encode(array_values($documents), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private function database(): bool
    {
        return is_string(config('firebase.project_id')) && config('firebase.project_id') !== ''
            && is_array(config('firebase.service_account')) && config('firebase.service_account') !== [];
    }
}
