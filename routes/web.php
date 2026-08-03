<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ContentController as AdminContentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/services', [PublicController::class, 'services'])->name('services');
Route::get('/doctors', [PublicController::class, 'doctors'])->name('doctors');
Route::get('/gallery', [PublicController::class, 'gallery'])->name('gallery');
Route::get('/faq', [PublicController::class, 'faq'])->name('faq');
Route::get('/testimonials', [PublicController::class, 'testimonials'])->name('testimonials');

Route::get('/book-appointment', [AppointmentController::class, 'create'])->name('appointment.create');
Route::post('/book-appointment', [AppointmentController::class, 'store'])->name('appointment.store');
Route::post('/book-appointment/availability', [AppointmentController::class, 'availability'])->name('appointment.availability');

Route::prefix('admin')->name('admin.')->group(function (): void {
    // Redirect bare /admin to the admin login page
    Route::get('/', fn () => redirect()->route('admin.login'));
    Route::middleware('admin.guest')->group(function (): void {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.store');
    });

    Route::middleware('admin.auth')->group(function (): void {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
        Route::patch('/appointments/{id}/approve', [AdminAppointmentController::class, 'approve'])->name('appointments.approve');
        Route::patch('/appointments/{id}/reject', [AdminAppointmentController::class, 'reject'])->name('appointments.reject');
        Route::delete('/appointments/{id}', [AdminAppointmentController::class, 'destroy'])->name('appointments.destroy');

        Route::get('/{collection}', [AdminContentController::class, 'index'])->whereIn('collection', ['services', 'testimonials', 'gallery', 'availability'])->name('content.index');
        Route::post('/{collection}', [AdminContentController::class, 'store'])->whereIn('collection', ['services', 'testimonials', 'gallery', 'availability'])->name('content.store');
        Route::patch('/{collection}/{id}', [AdminContentController::class, 'update'])->whereIn('collection', ['services', 'testimonials', 'gallery', 'availability'])->name('content.update');
        Route::delete('/{collection}/{id}', [AdminContentController::class, 'destroy'])->whereIn('collection', ['services', 'testimonials', 'gallery', 'availability'])->name('content.destroy');
    });
});
