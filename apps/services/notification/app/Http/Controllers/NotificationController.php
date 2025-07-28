<?php

namespace App\Http\Controllers;

use App\UseCases\CreateNotificationUseCase;
use App\UseCases\GetNotificationsUseCase;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function __construct(
        private CreateNotificationUseCase $createNotificationUseCase,
        private GetNotificationsUseCase $getNotificationsUseCase,
        private NotificationService $notificationService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['type', 'status', 'recipient_type', 'recipient_id', 'pending', 'failed', 'retryable', 'paginate', 'per_page']);

        $result = $this->getNotificationsUseCase->execute($filters);

        if ($result['success']) {
            return response()->json($result, 200);
        }

        return response()->json($result, 400);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->all();

        $result = $this->createNotificationUseCase->execute($data);

        if ($result['success']) {
            return response()->json($result, 201);
        }

        return response()->json($result, 400);
    }

    public function show(int $id): JsonResponse
    {
        $notification = $this->notificationService->getNotificationById($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification found',
            'data' => $notification
        ], 200);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->all();

        $notification = $this->notificationService->updateNotification($id, $data);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification updated successfully',
            'data' => $notification
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->notificationService->deleteNotification($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ], 200);
    }

    public function stats(): JsonResponse
    {
        $stats = $this->notificationService->getNotificationStats();

        return response()->json([
            'success' => true,
            'message' => 'Notification statistics retrieved',
            'data' => $stats
        ], 200);
    }

    public function retry(int $id): JsonResponse
    {
        $retried = $this->notificationService->retryFailedNotification($id);

        if (!$retried) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found or cannot be retried'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification retry initiated successfully'
        ], 200);
    }

    public function processScheduled(): JsonResponse
    {
        $processed = $this->notificationService->processScheduledNotifications();

        return response()->json([
            'success' => true,
            'message' => 'Scheduled notifications processed',
            'data' => [
                'processed_count' => $processed
            ]
        ], 200);
    }

    public function cleanup(Request $request): JsonResponse
    {
        $days = $request->input('days', 30);
        $deleted = $this->notificationService->cleanupOldNotifications($days);

        return response()->json([
            'success' => true,
            'message' => 'Old notifications cleaned up',
            'data' => [
                'deleted_count' => $deleted,
                'days' => $days
            ]
        ], 200);
    }

    public function send(int $id): JsonResponse
    {
        $notification = $this->notificationService->getNotificationById($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        $sent = $this->notificationService->sendNotification($notification);

        if ($sent) {
            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send notification'
        ], 500);
    }
} 