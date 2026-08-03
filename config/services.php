<?php

declare(strict_types=1);

return [
    'firebase' => [
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'auth_api_key' => env('FIREBASE_AUTH_API_KEY'),
        'storage_bucket' => env('FIREBASE_STORAGE_BUCKET'),
        'database_url' => env('FIREBASE_DATABASE_URL'),
    ],
];
