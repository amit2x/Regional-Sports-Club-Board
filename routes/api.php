<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\NotificationController as ApiNotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/v1/login', [AuthController::class, 'login']);
Route::post('/v1/google-login', [AuthController::class, 'googleLogin']);

// Protected routes
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Events
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}', [EventController::class, 'show']);
    Route::post('/events/{eventId}/register', [EventController::class, 'register']);
    Route::get('/my-registrations', [EventController::class, 'myRegistrations']);
    Route::get('/registrations/{id}', [EventController::class, 'registrationDetails']);

    // Notifications
    Route::get('/notifications', [ApiNotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [ApiNotificationController::class, 'unreadCount']);
    Route::post('/notifications/{id}/read', [ApiNotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [ApiNotificationController::class, 'markAllAsRead']);

    // Dashboard
    Route::get('/dashboard', [EventController::class, 'dashboard']);

    // Certificates
    Route::get('/certificates', [EventController::class, 'certificates']);
    Route::get('/certificates/{id}/download', [EventController::class, 'downloadCertificate']);
});
