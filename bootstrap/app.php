<?php

declare(strict_types=1);

use App\Http\Middleware\EnsureAdminAuthenticated;
use App\Http\Middleware\EnsureGuestAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.auth' => EnsureAdminAuthenticated::class,
            'admin.guest' => EnsureGuestAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Centralize exception handling in Laravel defaults.
    })
    ->create();
