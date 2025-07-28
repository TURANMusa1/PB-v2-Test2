<?php

if (!function_exists('l')) {
    /**
     * Custom logging function for PeopleBox ATS
     * 
     * @param string $message Log message
     * @param string $type Log type (debug, info, warning, error)
     * @param array $options Additional options
     */
    function l(string $message, string $type = 'info', array $options = []): void
    {
        $context = array_merge([
            'service' => 'auth',
            'timestamp' => now()->toISOString(),
        ], $options);

        switch ($type) {
            case 'debug':
                \Log::debug($message, $context);
                break;
            case 'info':
                \Log::info($message, $context);
                break;
            case 'warning':
                \Log::warning($message, $context);
                break;
            case 'error':
                \Log::error($message, $context);
                break;
            default:
                \Log::info($message, $context);
        }

        // If notification is enabled in options
        if (isset($options['notify']) && $options['notify']) {
            // TODO: Implement notification logic
            // This could send Slack/Discord notifications for important events
        }
    }
} 