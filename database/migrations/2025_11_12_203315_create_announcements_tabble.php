<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // public function up(): void
    // {
    //     Schema::create('announcements', function (Blueprint $table) {
    //         $table->id();
    //         $table->unsignedBigInteger('section_id');
    //         $table->unsignedBigInteger('user_id');
    //         $table->text('content');
    //         $table->timestamps();


    //         // Foreign key constraints
    //         $table->foreign('section_id')
    //             ->references('id')
    //             ->on('sections')
    //             ->onDelete('cascade');

    //         $table->foreign('user_id')
    //             ->references('id')
    //             ->on('users')
    //             ->onDelete('cascade');
    //     });
    // }

    // public function down(): void
    // {
    //     Schema::dropIfExists('announcements');
    // }
};

