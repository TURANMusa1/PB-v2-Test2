<?php

namespace App\UseCases;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateNotificationUseCase
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function execute(array $data): array
    {
        l('CreateNotificationUseCase: Starting notification creation', 'info');

        try {
            // Validate input data
            $this->validateInput($data);

            // Create notification
            $notification = $this->notificationService->createNotification($data);

            l('CreateNotificationUseCase: Notification created successfully', 'info', [
                'notification_id' => $notification->id,
                'type' => $notification->type,
                'channel' => $notification->channel
            ]);

            return [
                'success' => true,
                'message' => 'Notification created successfully',
                'data' => $notification
            ];

        } catch (ValidationException $e) {
            l('CreateNotificationUseCase: Validation failed', 'error', [
                'errors' => $e->errors()
            ]);

            return [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ];

        } catch (\Exception $e) {
            l('CreateNotificationUseCase: Unexpected error', 'error', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to create notification',
                'error' => $e->getMessage()
            ];
        }
    }

    private function validateInput(array $data): void
    {
        $rules = [
            'type' => 'required|in:email,sms,push,in_app',
            'channel' => 'required|in:queue,immediate',
            'recipient_type' => 'required|in:user,candidate,admin',
            'recipient_id' => 'required|integer',
            'subject' => 'nullable|string|max:255',
            'content' => 'required|string',
            'data' => 'nullable|array',
            'scheduled_at' => 'nullable|date|after:now',
            'template_name' => 'nullable|string|max:255',
            'attachments' => 'nullable|array',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
} 