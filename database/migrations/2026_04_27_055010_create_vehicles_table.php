<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('brand');
            $table->string('model');
            $table->string('version')->nullable();
            $table->string('plate')->nullable();
            $table->string('vin')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('fuel')->nullable();
            $table->string('engine')->nullable();
            $table->unsignedInteger('mileage')->default(0);
            $table->string('color')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};