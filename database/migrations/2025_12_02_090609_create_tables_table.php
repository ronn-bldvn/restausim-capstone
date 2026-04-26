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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floorplan_id')->constrained('floor_plans')->cascadeOnDelete();
            $table->string('name');
            $table->integer('capacity');
            $table->string('shape')->default('circle');
            $table->integer('x')->default(200);
            $table->integer('y')->default(200);
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('rotation')->default(0);
            $table->string('status')->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
