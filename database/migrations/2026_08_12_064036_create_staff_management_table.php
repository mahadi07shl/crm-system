<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_management', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('position')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Role/Status — SRS section 2
            $table->string('role')->default('User');   // Admin | Supervisor | User
            $table->string('status')->default('active'); // active | inactive

            // Extended profile fields
            $table->string('phone')->nullable();
            $table->string('profile_picture')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users_management');
    }
};