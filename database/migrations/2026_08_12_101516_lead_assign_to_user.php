<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Run this ONLY if your `leads` table does not already have `assigned_to`.
 * Check first with: Schema::getColumnListing('leads') in tinker.
 * If the column already exists (per the original Lead entity migration),
 * delete this file instead of running it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (! Schema::hasColumn('leads', 'assigned_to')) {
                $table->foreignId('assigned_to')->nullable()->after('category_id')
                    ->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'assigned_to')) {
                $table->dropConstrainedForeignId('assigned_to');
            }
        });
    }
};