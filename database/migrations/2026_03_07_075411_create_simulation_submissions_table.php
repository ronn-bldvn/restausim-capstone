<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulation_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('batch_code')->unique();
            $table->timestamp('submitted_at')->nullable();
            $table->string('status')->default('submitted'); // submitted | graded
            $table->decimal('score', 8, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->longText('summary')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulation_submissions');
    }
};