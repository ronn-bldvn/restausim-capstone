<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimulationAttemptsTable extends Migration
{
    // public function up()
    // {
    //     Schema::create('simulation_attempts', function (Blueprint $table) {
    //         $table->id('attempt_id');

    //         // match your DB exactly
    //         $table->unsignedBigInteger('student_id');      // users.id = bigint unsigned
    //         $table->unsignedInteger('section_id');         // section.section_id = int unsigned
    //         $table->unsignedInteger('activity_id');        // activities.activity_id = int unsigned

    //         $table->integer('auto_score')->nullable();
    //         $table->integer('faculty_score')->nullable();
    //         $table->text('faculty_remarks')->nullable();

    //         $table->timestamp('started_at')->nullable();
    //         $table->timestamp('finished_at')->nullable();

    //         $table->timestamps();

    //         // foreign keys
    //         $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
    //         $table->foreign('section_id')->references('section_id')->on('section')->onDelete('cascade');
    //         $table->foreign('activity_id')->references('activity_id')->on('activities')->onDelete('cascade');
    //     });
    // }

    // public function down()
    // {
    //     Schema::dropIfExists('simulation_attempts');
    // }
}
