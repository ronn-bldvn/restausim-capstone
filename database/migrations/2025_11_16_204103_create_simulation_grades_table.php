<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimulationGradesTable extends Migration
{
    public function up()
    {
        Schema::create('simulation_grades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('attempt_id');
            $table->unsignedBigInteger('faculty_id'); // users.id of the faculty
            $table->integer('score')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('attempt_id')->references('attempt_id')->on('simulation_attempts')->onDelete('cascade');
            $table->foreign('faculty_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique('attempt_id'); // one grade per attempt (adjust if you allow multiple reviews)
        });
    }

    public function down()
    {
        Schema::dropIfExists('simulation_grades');
    }
}
