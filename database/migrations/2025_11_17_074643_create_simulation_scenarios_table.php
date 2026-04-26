<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulation_scenarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('activity_id')
                ->constrained('activities', 'activity_id')
                ->onDelete('cascade');

            $table->string('scenario_type');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('parameters')->nullable();
            $table->json('grading_rubric')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulation_scenarios');
    }
};
