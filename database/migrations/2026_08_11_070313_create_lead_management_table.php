<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            $table->string('company_name');
            $table->string('contact_name');
            $table->string('email')->nullable();  // no duplicate check per SRS section 6
            $table->string('phone')->nullable();  // no duplicate check per SRS section 6
            $table->string('designation')->nullable();
            $table->text('remark')->nullable();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('status')->default('New'); // fixed enum, see App\Enums\LeadStatus

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('follow_up_date')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            $table->index('status');
            $table->index('assigned_to');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};