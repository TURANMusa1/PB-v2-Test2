<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample email notifications
        Notification::create([
            'type' => 'email',
            'channel' => 'queue',
            'recipient_type' => 'user',
            'recipient_id' => 1,
            'subject' => 'Welcome to PeopleBox ATS',
            'content' => 'Thank you for joining our platform. We hope you find it useful for your recruitment needs.',
            'status' => 'sent',
            'sent_at' => now()->subHours(2),
        ]);

        Notification::create([
            'type' => 'email',
            'channel' => 'immediate',
            'recipient_type' => 'candidate',
            'recipient_id' => 1,
            'subject' => 'Application Received',
            'content' => 'Your application has been received and is under review. We will contact you soon.',
            'status' => 'pending',
        ]);

        // Sample SMS notifications
        Notification::create([
            'type' => 'sms',
            'channel' => 'queue',
            'recipient_type' => 'candidate',
            'recipient_id' => 2,
            'content' => 'Your interview is scheduled for tomorrow at 10:00 AM. Please confirm.',
            'status' => 'sent',
            'sent_at' => now()->subHours(1),
        ]);

        // Sample push notifications
        Notification::create([
            'type' => 'push',
            'channel' => 'immediate',
            'recipient_type' => 'user',
            'recipient_id' => 1,
            'subject' => 'New Candidate Application',
            'content' => 'A new candidate has applied for the Senior Developer position.',
            'status' => 'pending',
        ]);

        // Sample in-app notifications
        Notification::create([
            'type' => 'in_app',
            'channel' => 'immediate',
            'recipient_type' => 'admin',
            'recipient_id' => 1,
            'subject' => 'System Update',
            'content' => 'The system will be under maintenance tonight from 2:00 AM to 4:00 AM.',
            'status' => 'sent',
            'sent_at' => now()->subMinutes(30),
        ]);

        // Sample failed notification
        Notification::create([
            'type' => 'email',
            'channel' => 'queue',
            'recipient_type' => 'user',
            'recipient_id' => 3,
            'subject' => 'Failed Email Test',
            'content' => 'This is a test email that should fail.',
            'status' => 'failed',
            'failed_at' => now()->subMinutes(15),
            'error_message' => 'SMTP connection timeout',
            'retry_count' => 2,
        ]);

        // Sample scheduled notification
        Notification::create([
            'type' => 'email',
            'channel' => 'queue',
            'recipient_type' => 'candidate',
            'recipient_id' => 3,
            'subject' => 'Reminder: Interview Tomorrow',
            'content' => 'This is a reminder that you have an interview scheduled for tomorrow.',
            'status' => 'pending',
            'scheduled_at' => now()->addHours(12),
        ]);

        $this->command->info('Notification seeder completed successfully!');
    }
}
