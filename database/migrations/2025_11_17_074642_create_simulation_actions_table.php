<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulation_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('simulation_sessions')->onDelete('cascade');
            $table->string('action_type');
            $table->json('action_data');
            $table->timestamp('timestamp');
            $table->boolean('is_correct')->nullable();
            $table->decimal('points_earned', 5, 2)->nullable();
            $table->timestamps();

            $table->index('session_id');
            $table->index('action_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulation_actions');
    }
};
