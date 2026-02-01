<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VisitorController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\DashboardController;


// Public Routes
Route::post('/auth/login', [AuthController::class, 'login']);

// Visitor Public Routes (Check-in)
Route::post('/visitors', [VisitorController::class, 'store']);

// Feedback Public Routes
Route::post('/feedback', [FeedbackController::class, 'store']);

// Public Dashboard Data (for Landing Page)
Route::get('/visitors/recent', [DashboardController::class, 'recentVisitors']);

// Protected Routes (Sanctum Auth)
Route::middleware('auth:sanctum')->group(function () {

    // Auth Management
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
    Route::post('/auth/upload-avatar', [AuthController::class, 'uploadAvatar']);

    // Admin Only Routes
    Route::middleware('admin')->group(function () {
        // Admin Registration
        Route::post('/auth/register', [AuthController::class, 'register']);

        // User Management
        Route::apiResource('users', UserController::class);

        // Feedback Management (Admin only list & delete)
        Route::get('/feedback', [FeedbackController::class, 'index']);
        Route::get('/feedback/{id}', [FeedbackController::class, 'show']);
        Route::delete('/feedback/{id}', [FeedbackController::class, 'destroy']);

        // Dashboard Stats
        Route::prefix('dashboard')->group(function () {
            Route::get('/stats', [DashboardController::class, 'stats']);
            Route::get('/recent-visitors', [DashboardController::class, 'recentVisitors']);
            Route::get('/rating-breakdown', [DashboardController::class, 'ratingBreakdown']);
            Route::get('/visitor-trends', [DashboardController::class, 'visitorTrends']);
        });

        // Visitor Management (Admin exclusive actions if any, but most are shared)
        Route::delete('/visitors/{id}', [VisitorController::class, 'destroy']);
    });

    // Admin & Receptionist Routes
    Route::middleware('admin_or_receptionist')->group(function () {
        // Visitor Management
        Route::get('/visitors', [VisitorController::class, 'index']);
        Route::get('/visitors/{id}', [VisitorController::class, 'show']);
        Route::put('/visitors/{id}', [VisitorController::class, 'update']);
        Route::put('/visitors/{id}/status', [VisitorController::class, 'updateStatus']);
        Route::post('/visitors/{id}/checkout', [VisitorController::class, 'checkout']);
    });
});
