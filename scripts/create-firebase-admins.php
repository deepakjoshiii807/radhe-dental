<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$serviceAccount = config('firebase.service_account');
$projectId = config('firebase.project_id');

if (! is_array($serviceAccount) || empty($serviceAccount) || ! is_string($projectId) || $projectId === '') {
    echo "Firebase service account or project ID is not configured.\n";
    exit(1);
}

$privateKey = $serviceAccount['private_key'] ?? null;
$clientEmail = $serviceAccount['client_email'] ?? null;

if (! is_string($privateKey) || ! is_string($clientEmail)) {
    echo "Service account missing private_key or client_email.\n";
    exit(1);
}

$now = time();
$header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
$claims = base64_encode(json_encode([
    'iss' => $clientEmail,
    'scope' => 'https://www.googleapis.com/auth/identitytoolkit https://www.googleapis.com/auth/cloud-platform',
    'aud' => 'https://oauth2.googleapis.com/token',
    'iat' => $now,
    'exp' => $now + 3600,
]));

openssl_sign($header.'.'.$claims, $signature, $privateKey, OPENSSL_ALGO_SHA256);
$assertion = $header.'.'.$claims.'.'.rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

$response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
    'assertion' => $assertion,
]);

if (! $response->successful()) {
    echo "Failed to obtain access token: {$response->body()}\n";
    exit(1);
}

$accessToken = $response->json('access_token');

$admins = [
    [
        'localId' => 'clinic-admin',
        'email' => 'rashan.pani470@gmail.com',
        'displayName' => 'Clinic Admin',
        'password' => 'SecretP@ssw0rd!',
        'emailVerified' => true,
    ],
];

$resp = Http::withToken($accessToken)->post("https://identitytoolkit.googleapis.com/v1/projects/{$projectId}/accounts:batchCreate", [
    'accounts' => $admins,
]);

if (! $resp->successful()) {
    echo "Failed to create admin users: {$resp->body()}\n";
    exit(1);
}

echo "Created/Updated admin users:\n";
print_r($resp->json());

echo "Done.\n";
