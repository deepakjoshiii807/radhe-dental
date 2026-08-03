<?php

declare(strict_types=1);

return [
    'project_id' => env('FIREBASE_PROJECT_ID'),
    'auth_api_key' => env('FIREBASE_AUTH_API_KEY'),
    'storage_bucket' => env('FIREBASE_STORAGE_BUCKET'),
    'database_url' => env('FIREBASE_DATABASE_URL'),
    'ca_bundle' => env('FIREBASE_CA_BUNDLE', base_path('resources/certs/cacert.pem')),
    'service_account' => array_filter([
        'type' => 'service_account',
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'private_key' => env('FIREBASE_PRIVATE_KEY') ? str_replace('\\n', "\n", env('FIREBASE_PRIVATE_KEY')) : null,
        'client_email' => env('FIREBASE_CLIENT_EMAIL'),
    ]),
];
