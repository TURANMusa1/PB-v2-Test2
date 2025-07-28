<?php

if (!function_exists('l')) {
    function l(string $message, string $type = 'info', array $context = []): void
    {
        $timestamp = now()->toISOString();
        $contextStr = !empty($context) ? ' | Context: ' . json_encode($context) : '';
        
        $logMessage = "[{$timestamp}] [{$type}] {$message}{$contextStr}";
        
        // Log to Laravel's log system
        \Illuminate\Support\Facades\Log::log($type, $message, $context);
        
        // Also output to console for development (only in CLI)
        if (app()->environment('local') && app()->runningInConsole()) {
            echo $logMessage . PHP_EOL;
        }
    }
} 