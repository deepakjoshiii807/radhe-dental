<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Firebase\FirebaseFactory;
use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(FirebaseFactory::class, static fn (): FirebaseFactory => new FirebaseFactory());
        $this->app->singleton(FirestoreRepository::class, static fn ($app): FirestoreRepository => new FirestoreRepository($app->make(FirebaseFactory::class)));
    }

    public function boot(): void
    {
        Blade::componentNamespace('App\\View\\Components', 'app');

        View::composer('*', static function ($view): void {
            $view->with('clinicBrand', config('clinic.name'));
        });
    }
}
