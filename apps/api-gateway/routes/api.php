<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiGatewayController;

/*
|--------------------------------------------------------------------------
| API Gateway Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Health check
Route::get('/health', [ApiGatewayController::class, 'health']);

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [ApiGatewayController::class, 'authLogin']);
    Route::post('/register', [ApiGatewayController::class, 'authRegister']);
    
    // Protected routes (will be handled by auth service)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [ApiGatewayController::class, 'authLogout']);
        Route::get('/me', [ApiGatewayController::class, 'authMe']);
        Route::post('/refresh', [ApiGatewayController::class, 'authRefresh']);
    });
});

// Candidate routes
Route::prefix('candidates')->group(function () {
    Route::get('/', [ApiGatewayController::class, 'candidateIndex']);
    Route::post('/', [ApiGatewayController::class, 'candidateStore']);
    Route::get('/stats', [ApiGatewayController::class, 'candidateStats']);
    Route::get('/{id}', [ApiGatewayController::class, 'candidateShow']);
    Route::put('/{id}', [ApiGatewayController::class, 'candidateUpdate']);
    Route::delete('/{id}', [ApiGatewayController::class, 'candidateDestroy']);
    Route::patch('/{id}/status', [ApiGatewayController::class, 'candidateUpdateStatus']);
    Route::patch('/{id}/contact', [ApiGatewayController::class, 'candidateUpdateContact']);
});

Route::prefix('notifications')->group(function () {
    Route::get('/', [ApiGatewayController::class, 'notificationIndex']);
    Route::post('/', [ApiGatewayController::class, 'notificationStore']);
    Route::get('/stats', [ApiGatewayController::class, 'notificationStats']);
    Route::get('/{id}', [ApiGatewayController::class, 'notificationShow']);
    Route::put('/{id}', [ApiGatewayController::class, 'notificationUpdate']);
    Route::delete('/{id}', [ApiGatewayController::class, 'notificationDestroy']);
    Route::post('/{id}/retry', [ApiGatewayController::class, 'notificationRetry']);
    Route::post('/{id}/send', [ApiGatewayController::class, 'notificationSend']);
    Route::post('/process-scheduled', [ApiGatewayController::class, 'notificationProcessScheduled']);
    Route::post('/cleanup', [ApiGatewayController::class, 'notificationCleanup']);
}); 