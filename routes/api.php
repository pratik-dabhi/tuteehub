<?php

use App\Http\Controllers\API\V1\SubscriptionController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('users/register', [AuthController::class,'register']);
Route::post('login', [AuthController::class,'login']);

// Routes that require authentication
Route::middleware('auth:sanctum')->group(function () {

    // subscription management
    Route::post('subscribe', [SubscriptionController::class,'subscribe']);
    Route::get('subscription/status', [SubscriptionController::class,'status']);
    Route::post('subscription/cancel', [SubscriptionController::class,'cancel']);

    Route::middleware(['ensure.subscription.active', 'throttle:subscription'])->group(function () {
        Route::get('users/{id}', [UserController::class,'show']);
    });
});
