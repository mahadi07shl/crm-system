<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_notifications', function (Blueprint $table) {
            $table->id();

            // Who receives the notification (SRS 8: assignee gets an in-app alert)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Which lead this notification is about
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();

            // Who performed the assignment (Admin or Supervisor)
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();

            $table->string('message');
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_notifications');
    }
};