<?php

namespace App\UseCases;

use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class GetNotificationsUseCase
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function execute(array $filters = []): array
    {
        l('GetNotificationsUseCase: Starting notification retrieval', 'info', $filters);

        try {
            $result = [];

            // Handle different filter types
            if (isset($filters['type'])) {
                $notifications = $this->notificationService->getNotificationsByType($filters['type']);
                $result = [
                    'success' => true,
                    'message' => 'Notifications found by type',
                    'data' => $notifications,
                    'total' => $notifications->count()
                ];
            } elseif (isset($filters['status'])) {
                $notifications = $this->notificationService->getNotificationsByStatus($filters['status']);
                $result = [
                    'success' => true,
                    'message' => 'Notifications found by status',
                    'data' => $notifications,
                    'total' => $notifications->count()
                ];
            } elseif (isset($filters['recipient_type']) && isset($filters['recipient_id'])) {
                $notifications = $this->notificationService->getNotificationsByRecipient(
                    $filters['recipient_type'],
                    $filters['recipient_id']
                );
                $result = [
                    'success' => true,
                    'message' => 'Notifications found for recipient',
                    'data' => $notifications,
                    'total' => $notifications->count()
                ];
            } elseif (isset($filters['pending'])) {
                $notifications = $this->notificationService->getPendingNotifications();
                $result = [
                    'success' => true,
                    'message' => 'Pending notifications found',
                    'data' => $notifications,
                    'total' => $notifications->count()
                ];
            } elseif (isset($filters['failed'])) {
                $notifications = $this->notificationService->getFailedNotifications();
                $result = [
                    'success' => true,
                    'message' => 'Failed notifications found',
                    'data' => $notifications,
                    'total' => $notifications->count()
                ];
            } elseif (isset($filters['retryable'])) {
                $notifications = $this->notificationService->getRetryableNotifications();
                $result = [
                    'success' => true,
                    'message' => 'Retryable notifications found',
                    'data' => $notifications,
                    'total' => $notifications->count()
                ];
            } elseif (isset($filters['paginate'])) {
                $perPage = $filters['per_page'] ?? 15;
                $notifications = $this->notificationService->paginateNotifications($perPage);
                $result = [
                    'success' => true,
                    'message' => 'Notifications paginated',
                    'data' => $notifications->items(),
                    'pagination' => [
                        'current_page' => $notifications->currentPage(),
                        'last_page' => $notifications->lastPage(),
                        'per_page' => $notifications->perPage(),
                        'total' => $notifications->total(),
                        'from' => $notifications->firstItem(),
                        'to' => $notifications->lastItem(),
                    ]
                ];
            } else {
                // Get all notifications
                $notifications = $this->notificationService->getAllNotifications();
                $result = [
                    'success' => true,
                    'message' => 'All notifications retrieved',
                    'data' => $notifications,
                    'total' => $notifications->count()
                ];
            }

            l('GetNotificationsUseCase: Notifications retrieved successfully', 'info', [
                'total' => $result['total'] ?? count($result['data'])
            ]);

            return $result;

        } catch (\Exception $e) {
            l('GetNotificationsUseCase: Error retrieving notifications', 'error', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to retrieve notifications',
                'error' => $e->getMessage()
            ];
        }
    }
} 