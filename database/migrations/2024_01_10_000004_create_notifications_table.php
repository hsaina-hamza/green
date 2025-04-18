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
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Additional fields for our waste management system
            $table->string('category')->nullable()->index();
            $table->string('priority')->default('normal')->index();
            $table->json('tags')->nullable();
            $table->string('status')->default('pending')->index();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('error')->nullable();
            $table->integer('attempts')->default(0);
            $table->json('channels')->nullable();
            $table->json('metadata')->nullable();

            // Indexes for common queries
            $table->index(['notifiable_type', 'notifiable_id', 'read_at']);
            $table->index(['created_at', 'priority']);
            $table->index(['status', 'created_at']);
        });

        // Create a table for notification preferences
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->morphs('notifiable');
            $table->string('channel');
            $table->string('category')->nullable();
            $table->boolean('enabled')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicates
            $table->unique(['notifiable_type', 'notifiable_id', 'channel', 'category'], 'notification_preferences_unique');
        });

        // Create a table for notification failures
        Schema::create('notification_failures', function (Blueprint $table) {
            $table->id();
            $table->uuid('notification_id');
            $table->string('channel');
            $table->text('error');
            $table->json('context')->nullable();
            $table->timestamp('failed_at');
            $table->timestamps();

            $table->foreign('notification_id')
                ->references('id')
                ->on('notifications')
                ->onDelete('cascade');

            $table->index(['notification_id', 'channel']);
            $table->index('failed_at');
        });

        // Create a table for notification schedules
        Schema::create('notification_schedules', function (Blueprint $table) {
            $table->id();
            $table->morphs('notifiable');
            $table->string('type');
            $table->json('data');
            $table->timestamp('scheduled_at');
            $table->string('frequency')->nullable(); // daily, weekly, monthly, etc.
            $table->json('schedule_metadata')->nullable();
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamp('next_send_at')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['notifiable_type', 'notifiable_id', 'active']);
            $table->index(['scheduled_at', 'active']);
            $table->index(['next_send_at', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_schedules');
        Schema::dropIfExists('notification_failures');
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('notifications');
    }
};
