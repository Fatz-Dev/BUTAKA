<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ReceptionistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);


// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Admin Specific Routes (Management of Receptionists)
    Route::apiResource('receptionists', ReceptionistController::class);

    // Receptionist & Admin Routes (Management of Guests)
    Route::apiResource('guests', GuestController::class);

    // Shared Routes
    Route::apiResource('feedback', FeedbackController::class)->only(['index', 'destroy']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
});


