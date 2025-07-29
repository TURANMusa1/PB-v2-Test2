<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'PeopleBox ATS v2 - Candidate Service',
        'version' => '2.0.0',
        'status' => 'running',
        'service' => 'candidate',
        'endpoints' => [
            'index' => '/api/candidates',
            'store' => '/api/candidates',
            'show' => '/api/candidates/{id}',
            'update' => '/api/candidates/{id}',
            'delete' => '/api/candidates/{id}',
            'stats' => '/api/candidates/stats',
            'update_status' => '/api/candidates/{id}/status',
            'update_contact' => '/api/candidates/{id}/contact'
        ]
    ]);
});

// API routes'a yönlendirme
Route::get('/api', function () {
    return response()->json([
        'message' => 'Candidate Service API',
        'endpoints' => [
            'index' => '/api/candidates',
            'store' => '/api/candidates',
            'show' => '/api/candidates/{id}',
            'update' => '/api/candidates/{id}',
            'delete' => '/api/candidates/{id}',
            'stats' => '/api/candidates/stats',
            'update_status' => '/api/candidates/{id}/status',
            'update_contact' => '/api/candidates/{id}/contact'
        ]
    ]);
});
