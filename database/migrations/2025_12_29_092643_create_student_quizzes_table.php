<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('quiz_type')->default('pre-assessment'); // pre-assessment or post-assessment
            $table->integer('score');
            $table->json('answers'); // Store all answers
            $table->timestamp('completed_at');
            $table->timestamps();

            // Index for faster queries
            $table->index(['user_id', 'quiz_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_quizzes');
    }
};
