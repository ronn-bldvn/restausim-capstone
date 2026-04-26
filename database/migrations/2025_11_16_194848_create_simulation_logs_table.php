<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimulationLogsTable extends Migration
{
    public function up()
    {
        Schema::create('simulation_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('attempt_id');
            $table->string('action');
            $table->json('details')->nullable();
            $table->timestamp('action_time')->nullable();
            $table->timestamps();

            $table->foreign('attempt_id')->references('attempt_id')->on('simulation_attempts')->onDelete('cascade');
            $table->index('attempt_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('simulation_logs');
    }
}
