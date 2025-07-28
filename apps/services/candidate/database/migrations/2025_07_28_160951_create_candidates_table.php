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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('summary')->nullable();
            $table->string('experience_level')->default('entry'); // entry, mid, senior, expert
            $table->string('current_position')->nullable();
            $table->string('current_company')->nullable();
            $table->decimal('expected_salary', 10, 2)->nullable();
            $table->string('location')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('cv_file_path')->nullable();
            $table->json('skills')->nullable();
            $table->json('education')->nullable();
            $table->json('experience')->nullable();
            $table->string('status')->default('active'); // active, inactive, hired, rejected
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('last_contact_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['email', 'status']);
            $table->index(['company_id', 'status']);
            $table->index(['experience_level', 'status']);
            $table->index('created_by');
            $table->index('last_contact_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
