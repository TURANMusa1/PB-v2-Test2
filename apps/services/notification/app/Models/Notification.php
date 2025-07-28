<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'channel',
        'recipient_type',
        'recipient_id',
        'subject',
        'content',
        'data',
        'status',
        'sent_at',
        'failed_at',
        'error_message',
        'retry_count',
        'scheduled_at',
        'template_name',
        'attachments',
    ];

    protected $casts = [
        'data' => 'array',
        'attachments' => 'array',
        'sent_at' => 'datetime',
        'failed_at' => 'datetime',
        'scheduled_at' => 'datetime',
    ];

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isEmail(): bool
    {
        return $this->type === 'email';
    }

    public function isSMS(): bool
    {
        return $this->type === 'sms';
    }

    public function isPush(): bool
    {
        return $this->type === 'push';
    }

    public function isInApp(): bool
    {
        return $this->type === 'in_app';
    }

    public function isQueued(): bool
    {
        return $this->channel === 'queue';
    }

    public function isImmediate(): bool
    {
        return $this->channel === 'immediate';
    }

    public function markAsSent(): bool
    {
        return $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function markAsFailed(string $errorMessage = null): bool
    {
        return $this->update([
            'status' => 'failed',
            'failed_at' => now(),
            'error_message' => $errorMessage,
            'retry_count' => $this->retry_count + 1,
        ]);
    }

    public function canRetry(): bool
    {
        return $this->isFailed() && $this->retry_count < 3;
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'email' => 'Email',
            'sms' => 'SMS',
            'push' => 'Push Notification',
            'in_app' => 'In-App Notification',
            default => 'Unknown'
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'sent' => 'Sent',
            'failed' => 'Failed',
            'cancelled' => 'Cancelled',
            default => 'Unknown'
        };
    }
}
