<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'PeopleBox ATS v2 - Notification Service',
        'version' => '2.0.0',
        'status' => 'running',
        'service' => 'notification',
        'endpoints' => [
            'index' => '/api/notifications',
            'store' => '/api/notifications',
            'show' => '/api/notifications/{id}',
            'update' => '/api/notifications/{id}',
            'delete' => '/api/notifications/{id}',
            'stats' => '/api/notifications/stats',
            'retry' => '/api/notifications/{id}/retry',
            'send' => '/api/notifications/{id}/send',
            'process_scheduled' => '/api/notifications/process-scheduled',
            'cleanup' => '/api/notifications/cleanup'
        ]
    ]);
});

// API routes'a yönlendirme
Route::get('/api', function () {
    return response()->json([
        'message' => 'Notification Service API',
        'endpoints' => [
            'index' => '/api/notifications',
            'store' => '/api/notifications',
            'show' => '/api/notifications/{id}',
            'update' => '/api/notifications/{id}',
            'delete' => '/api/notifications/{id}',
            'stats' => '/api/notifications/stats',
            'retry' => '/api/notifications/{id}/retry',
            'send' => '/api/notifications/{id}/send',
            'process_scheduled' => '/api/notifications/process-scheduled',
            'cleanup' => '/api/notifications/cleanup'
        ]
    ]);
});
