<?php

declare(strict_types=1);

namespace App\Services\Firebase;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

final class AuthService
{
    public function __construct(private readonly FirestoreRepository $repository)
    {
    }

    public function attempt(string $email, string $password): array
    {
        $apiKey = config('firebase.auth_api_key');
        $caBundle = config('firebase.ca_bundle');

        if (! is_string($apiKey) || $apiKey === '') {
            throw ValidationException::withMessages([
                'email' => 'Firebase authentication is not configured.',
            ]);
        }

        $request = Http::asJson();

        if (is_string($caBundle) && $caBundle !== '' && is_file($caBundle)) {
            $request = $request->withOptions(['verify' => $caBundle]);
        }

        $response = $request->post(
            'https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key='.$apiKey,
            [
                'email' => $email,
                'password' => $password,
                'returnSecureToken' => true,
            ]
        );

        if ($response->failed()) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials are invalid.',
            ]);
        }

        $payload = $response->json();
        $adminProfile = null;
        foreach ($this->repository->all('admins') as $document) {
            if (($document['email'] ?? null) === $email) {
                $adminProfile = $document;
                break;
            }
        }

        if (! $adminProfile) {
            throw ValidationException::withMessages([
                'email' => 'This account is not authorized as a clinic administrator.',
            ]);
        }

        return [
            'uid' => $payload['localId'] ?? $adminProfile['id'],
            'email' => $payload['email'] ?? $email,
            'name' => $adminProfile['name'] ?? $email,
            'id_token' => $payload['idToken'] ?? null,
        ];
    }
}
