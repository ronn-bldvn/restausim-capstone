<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulation_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('activity_id')
                ->constrained('activities', 'activity_id')
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained('users', 'id')
                ->onDelete('cascade');

            $table->string('role_name');
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->enum('status', ['in_progress', 'submitted', 'graded'])->default('in_progress');
            $table->decimal('score', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->json('session_data')->nullable();
            $table->timestamps();

            $table->index(['activity_id', 'user_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulation_sessions');
    }
};
