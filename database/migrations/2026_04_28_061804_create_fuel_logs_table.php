<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->date('filled_at');
            $table->unsignedInteger('mileage_at');
            $table->decimal('liters', 8, 2);
            $table->decimal('cost', 10, 2);
            $table->decimal('price_per_liter', 6, 3)->nullable();
            $table->string('station')->nullable();
            $table->boolean('is_full_tank')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['vehicle_id', 'filled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_logs');
    }
};