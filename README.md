# Radhe Dental Clinic Management System

A production-ready Laravel 12 application for a modern dental clinic website and admin dashboard.

## Stack

- Laravel 12
- PHP 8.3+
- Blade templates
- Tailwind CSS
- Firebase Firestore
- Firebase Authentication for admin login

## Local setup

1. Install PHP 8.3+ and Composer.
2. Install dependencies:

   ```bash
   composer install
   npm install
   ```

3. Copy environment file and configure Firebase credentials:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Run the app:

   ```bash
   php artisan serve
   npm run dev
   ```

## Required environment variables

 - `APP_NAME`
 - `APP_URL`
 - `FIREBASE_PROJECT_ID`
 - `FIREBASE_PRIVATE_KEY`
 - `FIREBASE_CLIENT_EMAIL`
 - `FIREBASE_AUTH_API_KEY`
 - `FIREBASE_STORAGE_BUCKET`
 - `FIREBASE_DATABASE_URL`
 - `FIREBASE_CA_BUNDLE`

## Deployment notes

 - Keep `resources/certs/cacert.pem` in the deployed application so Firebase Auth requests can verify SSL certificates reliably.
 - Firestore reads and writes use the Firebase REST API, so the app does not require the PHP `grpc` extension.
 - If Firebase credentials are missing, the app falls back to local JSON storage under `storage/app/firebase-fallback` for development.

## Firebase collections


## Notes

