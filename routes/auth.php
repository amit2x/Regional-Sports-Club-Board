<?php

use App\Http\Controllers\Auth\EmployeeAuthController;
use Illuminate\Support\Facades\Route;

// Employee Authentication Routes
Route::prefix('employee')->name('employee.')->group(function () {
    Route::get('/login', [EmployeeAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [EmployeeAuthController::class, 'login']);
    Route::post('/logout', [EmployeeAuthController::class, 'logout'])->name('logout');

    // Password Management
    Route::get('/change-password', [EmployeeAuthController::class, 'showChangePasswordForm'])
        ->name('password.change')
        ->middleware('auth:employee');
    Route::post('/change-password', [EmployeeAuthController::class, 'changePassword'])
        ->middleware('auth:employee');

    // Forgot Password
    Route::get('/forgot-password', [EmployeeAuthController::class, 'showForgotPasswordForm'])
        ->name('password.request');
    Route::post('/forgot-password', [EmployeeAuthController::class, 'sendResetLink'])
        ->name('password.email');

    // Google OAuth
    Route::get('/auth/google', [EmployeeAuthController::class, 'redirectToGoogle'])
        ->name('auth.google');
    Route::get('/auth/google/callback', [EmployeeAuthController::class, 'handleGoogleCallback']);
});
