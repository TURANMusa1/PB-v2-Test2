<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class NotificationServiceProxy
{
    private string $notificationServiceUrl;

    public function __construct()
    {
        $this->notificationServiceUrl = env('NOTIFICATION_SERVICE_URL', 'http://localhost:8004');
    }

    public function getNotifications(array $filters = []): Response
    {
        try {
            return Http::timeout(10)->get($this->notificationServiceUrl . '/api/notifications', $filters);
        } catch (\Exception $e) {
            return Http::fake()->get('', [])->throw($e);
        }
    }

    public function getNotification(int $id): Response
    {
        try {
            return Http::timeout(10)->get($this->notificationServiceUrl . "/api/notifications/{$id}");
        } catch (\Exception $e) {
            return Http::fake()->get('', [])->throw($e);
        }
    }

    public function createNotification(array $data): Response
    {
        try {
            return Http::timeout(10)->post($this->notificationServiceUrl . '/api/notifications', $data);
        } catch (\Exception $e) {
            return Http::fake()->post('', [])->throw($e);
        }
    }

    public function updateNotification(int $id, array $data): Response
    {
        try {
            return Http::timeout(10)->put($this->notificationServiceUrl . "/api/notifications/{$id}", $data);
        } catch (\Exception $e) {
            return Http::fake()->put('', [])->throw($e);
        }
    }

    public function deleteNotification(int $id): Response
    {
        try {
            return Http::timeout(10)->delete($this->notificationServiceUrl . "/api/notifications/{$id}");
        } catch (\Exception $e) {
            return Http::fake()->delete('', [])->throw($e);
        }
    }

    public function getNotificationStats(): Response
    {
        try {
            return Http::timeout(10)->get($this->notificationServiceUrl . '/api/notifications/stats');
        } catch (\Exception $e) {
            return Http::fake()->get('', [])->throw($e);
        }
    }

    public function retryNotification(int $id): Response
    {
        try {
            return Http::timeout(10)->post($this->notificationServiceUrl . "/api/notifications/{$id}/retry");
        } catch (\Exception $e) {
            return Http::fake()->post('', [])->throw($e);
        }
    }

    public function sendNotification(int $id): Response
    {
        try {
            return Http::timeout(10)->post($this->notificationServiceUrl . "/api/notifications/{$id}/send");
        } catch (\Exception $e) {
            return Http::fake()->post('', [])->throw($e);
        }
    }

    public function processScheduledNotifications(): Response
    {
        try {
            return Http::timeout(10)->post($this->notificationServiceUrl . '/api/notifications/process-scheduled');
        } catch (\Exception $e) {
            return Http::fake()->post('', [])->throw($e);
        }
    }

    public function cleanupNotifications(array $data = []): Response
    {
        try {
            return Http::timeout(10)->post($this->notificationServiceUrl . '/api/notifications/cleanup', $data);
        } catch (\Exception $e) {
            return Http::fake()->post('', [])->throw($e);
        }
    }

    public function health(): Response
    {
        return Http::get($this->notificationServiceUrl . '/api/health');
    }
} 