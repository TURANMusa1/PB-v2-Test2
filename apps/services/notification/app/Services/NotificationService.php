<?php

namespace App\Services;

use App\Models\Notification;
use App\Repositories\NotificationRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function __construct(
        private NotificationRepository $repository
    ) {}

    public function getAllNotifications(): Collection
    {
        l('Getting all notifications', 'info');
        return $this->repository->getAll();
    }

    public function getNotificationById(int $id): ?Notification
    {
        l("Getting notification by ID: {$id}", 'info');
        return $this->repository->findById($id);
    }

    public function createNotification(array $data): Notification
    {
        l('Creating new notification', 'info', ['type' => $data['type'] ?? 'unknown']);
        
        $notification = $this->repository->create($data);
        
        // If immediate channel, send immediately
        if ($notification->isImmediate()) {
            $this->sendNotification($notification);
        }
        
        return $notification;
    }

    public function updateNotification(int $id, array $data): ?Notification
    {
        l("Updating notification: {$id}", 'info');
        
        $notification = $this->repository->findById($id);
        if (!$notification) {
            return null;
        }

        $this->repository->update($notification, $data);
        
        return $notification->fresh();
    }

    public function deleteNotification(int $id): bool
    {
        l("Deleting notification: {$id}", 'info');
        
        $notification = $this->repository->findById($id);
        if (!$notification) {
            return false;
        }

        return $this->repository->delete($notification);
    }

    public function getPendingNotifications(): Collection
    {
        l('Getting pending notifications', 'info');
        return $this->repository->getPending();
    }

    public function getScheduledNotifications(): Collection
    {
        l('Getting scheduled notifications', 'info');
        return $this->repository->getScheduled();
    }

    public function getFailedNotifications(): Collection
    {
        l('Getting failed notifications', 'info');
        return $this->repository->getFailed();
    }

    public function getRetryableNotifications(): Collection
    {
        l('Getting retryable notifications', 'info');
        return $this->repository->getRetryable();
    }

    public function getNotificationsByType(string $type): Collection
    {
        l("Getting notifications by type: {$type}", 'info');
        return $this->repository->getByType($type);
    }

    public function getNotificationsByStatus(string $status): Collection
    {
        l("Getting notifications by status: {$status}", 'info');
        return $this->repository->getByStatus($status);
    }

    public function getNotificationsByRecipient(string $recipientType, int $recipientId): Collection
    {
        l("Getting notifications for recipient: {$recipientType}:{$recipientId}", 'info');
        return $this->repository->getByRecipient($recipientType, $recipientId);
    }

    public function paginateNotifications(int $perPage = 15): LengthAwarePaginator
    {
        l("Paginating notifications with per page: {$perPage}", 'info');
        return $this->repository->paginate($perPage);
    }

    public function getNotificationStats(): array
    {
        l('Getting notification statistics', 'info');
        return $this->repository->getStats();
    }

    public function sendNotification(Notification $notification): bool
    {
        l("Sending notification: {$notification->id}", 'info', [
            'type' => $notification->type,
            'recipient' => "{$notification->recipient_type}:{$notification->recipient_id}"
        ]);

        try {
            switch ($notification->type) {
                case 'email':
                    $result = $this->sendEmail($notification);
                    break;
                case 'sms':
                    $result = $this->sendSMS($notification);
                    break;
                case 'push':
                    $result = $this->sendPush($notification);
                    break;
                case 'in_app':
                    $result = $this->sendInApp($notification);
                    break;
                default:
                    throw new \Exception("Unknown notification type: {$notification->type}");
            }

            if ($result) {
                $notification->markAsSent();
                l("Notification sent successfully: {$notification->id}", 'info');
                return true;
            } else {
                $notification->markAsFailed('Sending failed');
                l("Notification failed to send: {$notification->id}", 'error');
                return false;
            }

        } catch (\Exception $e) {
            $notification->markAsFailed($e->getMessage());
            l("Notification error: {$notification->id}", 'error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function retryFailedNotification(int $id): bool
    {
        l("Retrying failed notification: {$id}", 'info');
        
        $notification = $this->repository->findById($id);
        if (!$notification || !$notification->canRetry()) {
            return false;
        }

        // Reset status to pending
        $notification->update(['status' => 'pending']);
        
        return $this->sendNotification($notification);
    }

    public function processScheduledNotifications(): int
    {
        l('Processing scheduled notifications', 'info');
        
        $scheduledNotifications = $this->getScheduledNotifications();
        $processed = 0;

        foreach ($scheduledNotifications as $notification) {
            if ($this->sendNotification($notification)) {
                $processed++;
            }
        }

        l("Processed {$processed} scheduled notifications", 'info');
        return $processed;
    }

    public function cleanupOldNotifications(int $days = 30): int
    {
        l("Cleaning up notifications older than {$days} days", 'info');
        return $this->repository->cleanupOldNotifications($days);
    }

    private function sendEmail(Notification $notification): bool
    {
        try {
            // For now, just log the email
            l("Sending email to: {$notification->recipient_id}", 'info', [
                'subject' => $notification->subject,
                'content' => $notification->content
            ]);

            // TODO: Implement actual email sending
            // Mail::to($notification->recipient_id)->send(new NotificationMail($notification));
            
            return true;
        } catch (\Exception $e) {
            l("Email sending failed", 'error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function sendSMS(Notification $notification): bool
    {
        try {
            l("Sending SMS to: {$notification->recipient_id}", 'info', [
                'content' => $notification->content
            ]);

            // TODO: Implement actual SMS sending
            // SMS::send($notification->recipient_id, $notification->content);
            
            return true;
        } catch (\Exception $e) {
            l("SMS sending failed", 'error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function sendPush(Notification $notification): bool
    {
        try {
            l("Sending push notification to: {$notification->recipient_id}", 'info', [
                'subject' => $notification->subject,
                'content' => $notification->content
            ]);

            // TODO: Implement actual push notification sending
            // PushNotification::send($notification->recipient_id, $notification);
            
            return true;
        } catch (\Exception $e) {
            l("Push notification sending failed", 'error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function sendInApp(Notification $notification): bool
    {
        try {
            l("Sending in-app notification to: {$notification->recipient_id}", 'info', [
                'subject' => $notification->subject,
                'content' => $notification->content
            ]);

            // TODO: Implement actual in-app notification sending
            // InAppNotification::send($notification->recipient_id, $notification);
            
            return true;
        } catch (\Exception $e) {
            l("In-app notification sending failed", 'error', ['error' => $e->getMessage()]);
            return false;
        }
    }
} 