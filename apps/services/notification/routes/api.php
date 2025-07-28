<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'notification',
        'timestamp' => now()->toISOString(),
    ]);
});

// Notification routes
Route::prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::post('/', [NotificationController::class, 'store']);
    Route::get('/stats', [NotificationController::class, 'stats']);
    Route::get('/{id}', [NotificationController::class, 'show']);
    Route::put('/{id}', [NotificationController::class, 'update']);
    Route::delete('/{id}', [NotificationController::class, 'destroy']);
    Route::post('/{id}/retry', [NotificationController::class, 'retry']);
    Route::post('/{id}/send', [NotificationController::class, 'send']);
    Route::post('/process-scheduled', [NotificationController::class, 'processScheduled']);
    Route::post('/cleanup', [NotificationController::class, 'cleanup']);
}); 