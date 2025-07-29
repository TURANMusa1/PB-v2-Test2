<?php

use Illuminate\Support\Facades\Route;

// API Gateway ana sayfası - API dokümantasyonuna yönlendir
Route::get('/', function () {
    return response()->json([
        'message' => 'PeopleBox ATS v2 - API Gateway',
        'version' => '2.0.0',
        'status' => 'running',
        'endpoints' => [
            'health' => '/api/health',
            'auth' => '/api/auth/*',
            'candidates' => '/api/candidates/*',
            'notifications' => '/api/notifications/*'
        ],
        'documentation' => 'API endpoints are available at /api/*'
    ]);
});

// API routes'a yönlendirme
Route::get('/api', function () {
    return redirect('/api/health');
});
