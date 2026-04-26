<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id('activity_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('grades')->nullable();
            $table->timestamp('due_date');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Correct foreign key constraints
            $table->foreign('section_id')
                ->references('section_id')
                ->on('section')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
};
