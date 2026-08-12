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
        $table->string('role')->default('User')->after('email');   // Admin | Supervisor | User
        $table->string('status')->default('active')->after('role');
        $table->string('phone')->nullable()->after('status');
        $table->string('profile_picture')->nullable()->after('phone');
        $table->string('gender')->nullable()->after('profile_picture');
        $table->text('address')->nullable()->after('gender');
        $table->string('emergency_contact_name')->nullable()->after('address');
        $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
