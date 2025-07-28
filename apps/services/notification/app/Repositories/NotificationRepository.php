<?php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationRepository
{
    public function __construct(
        private Notification $model
    ) {}

    public function findById(int $id): ?Notification
    {
        return $this->model->find($id);
    }

    public function create(array $data): Notification
    {
        return $this->model->create($data);
    }

    public function update(Notification $notification, array $data): bool
    {
        return $notification->update($data);
    }

    public function delete(Notification $notification): bool
    {
        return $notification->delete();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getPending(): Collection
    {
        return $this->model->where('status', 'pending')->get();
    }

    public function getByType(string $type): Collection
    {
        return $this->model->where('type', $type)->get();
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function getByRecipient(string $recipientType, int $recipientId): Collection
    {
        return $this->model->where('recipient_type', $recipientType)
                          ->where('recipient_id', $recipientId)
                          ->get();
    }

    public function getScheduled(): Collection
    {
        return $this->model->where('status', 'pending')
                          ->whereNotNull('scheduled_at')
                          ->where('scheduled_at', '<=', now())
                          ->get();
    }

    public function getFailed(): Collection
    {
        return $this->model->where('status', 'failed')->get();
    }

    public function getRetryable(): Collection
    {
        return $this->model->where('status', 'failed')
                          ->where('retry_count', '<', 3)
                          ->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getStats(): array
    {
        return [
            'total' => $this->model->count(),
            'pending' => $this->model->where('status', 'pending')->count(),
            'sent' => $this->model->where('status', 'sent')->count(),
            'failed' => $this->model->where('status', 'failed')->count(),
            'cancelled' => $this->model->where('status', 'cancelled')->count(),
            'email' => $this->model->where('type', 'email')->count(),
            'sms' => $this->model->where('type', 'sms')->count(),
            'push' => $this->model->where('type', 'push')->count(),
            'in_app' => $this->model->where('type', 'in_app')->count(),
        ];
    }

    public function getRecent(int $days = 7): Collection
    {
        return $this->model->where('created_at', '>=', now()->subDays($days))
                          ->orderBy('created_at', 'desc')
                          ->get();
    }

    public function cleanupOldNotifications(int $days = 30): int
    {
        return $this->model->where('created_at', '<', now()->subDays($days))
                          ->whereIn('status', ['sent', 'failed', 'cancelled'])
                          ->delete();
    }
} 