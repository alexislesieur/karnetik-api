<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('label');
            $table->text('notes')->nullable();
            $table->date('performed_at');
            $table->unsignedInteger('mileage_at')->nullable();
            $table->string('garage')->nullable();
            $table->string('garage_city')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->decimal('cost_parts', 10, 2)->nullable();
            $table->decimal('cost_labor', 10, 2)->nullable();
            $table->string('reference')->nullable();
            $table->boolean('is_professional')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['vehicle_id', 'performed_at']);
            $table->index(['vehicle_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};