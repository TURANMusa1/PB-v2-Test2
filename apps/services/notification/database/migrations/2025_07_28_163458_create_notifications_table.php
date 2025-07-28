<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // email, sms, push, in_app
            $table->string('channel'); // queue, immediate
            $table->string('recipient_type'); // user, candidate, admin
            $table->unsignedBigInteger('recipient_id');
            $table->string('subject')->nullable();
            $table->text('content');
            $table->json('data')->nullable(); // Additional data
            $table->string('status')->default('pending'); // pending, sent, failed, cancelled
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->integer('retry_count')->default(0);
            $table->timestamp('scheduled_at')->nullable();
            $table->string('template_name')->nullable(); // Email template name
            $table->json('attachments')->nullable(); // File attachments
            $table->timestamps();
            
            // Indexes
            $table->index(['type', 'status']);
            $table->index(['recipient_type', 'recipient_id']);
            $table->index(['status', 'scheduled_at']);
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
