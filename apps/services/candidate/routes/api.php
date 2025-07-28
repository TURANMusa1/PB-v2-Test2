<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidateController;

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

Route::prefix('candidates')->group(function () {
    Route::get('/', [CandidateController::class, 'index']);
    Route::post('/', [CandidateController::class, 'store']);
    Route::get('/stats', [CandidateController::class, 'stats']);
    Route::get('/{id}', [CandidateController::class, 'show']);
    Route::put('/{id}', [CandidateController::class, 'update']);
    Route::delete('/{id}', [CandidateController::class, 'destroy']);
    Route::patch('/{id}/status', [CandidateController::class, 'updateStatus']);
    Route::patch('/{id}/contact', [CandidateController::class, 'updateLastContact']);
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'candidate',
        'timestamp' => now()->toISOString(),
    ]);
}); 