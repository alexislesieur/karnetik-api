<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('label');
            $table->date('due_date')->nullable();
            $table->unsignedInteger('due_mileage')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->unsignedSmallInteger('recurrence_months')->nullable();
            $table->unsignedInteger('recurrence_km')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            $table->index(['vehicle_id', 'is_completed', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};