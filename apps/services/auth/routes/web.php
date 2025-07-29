<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'PeopleBox ATS v2 - Auth Service',
        'version' => '2.0.0',
        'status' => 'running',
        'service' => 'auth',
        'endpoints' => [
            'login' => '/api/auth/login',
            'register' => '/api/auth/register',
            'logout' => '/api/auth/logout',
            'me' => '/api/auth/me',
            'refresh' => '/api/auth/refresh'
        ]
    ]);
});

// API routes'a yönlendirme
Route::get('/api', function () {
    return response()->json([
        'message' => 'Auth Service API',
        'endpoints' => [
            'login' => '/api/auth/login',
            'register' => '/api/auth/register',
            'logout' => '/api/auth/logout',
            'me' => '/api/auth/me',
            'refresh' => '/api/auth/refresh'
        ]
    ]);
});
