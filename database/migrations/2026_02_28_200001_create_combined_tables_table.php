<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('combined_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floor_plan_id')->constrained('floor_plans')->cascadeOnDelete();
            $table->unsignedInteger('total_capacity')->default(0);
            $table->enum('status', ['available', 'occupied', 'reserved'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combined_tables');
    }
};
