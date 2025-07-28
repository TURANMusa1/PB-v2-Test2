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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('hr')->after('email');
            $table->unsignedBigInteger('company_id')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('company_id');
            
            // Indexes
            $table->index(['role', 'is_active']);
            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role', 'is_active']);
            $table->dropIndex(['company_id']);
            $table->dropColumn(['role', 'company_id', 'is_active']);
        });
    }
};
