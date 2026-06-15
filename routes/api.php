<?php

declare(strict_types=1);

use App\Http\Controllers\AnalyticsAIController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:login')->post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function (): void {
    Route::apiResource('orders', OrderController::class);

    Route::middleware('role:admin')->prefix('admin')->group(function (): void {
        Route::get('/analytics/dashboard', [AnalyticsAIController::class, 'dashboard']);
        Route::post('/ai/insights', [AnalyticsAIController::class, 'insight']);
    });
});

