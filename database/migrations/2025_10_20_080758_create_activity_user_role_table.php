<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::create('activity_user_role', function (Blueprint $table) {
        //     $table->id();

        //     $table->unsignedBigInteger('activity_id');
        //     $table->unsignedBigInteger('user_id');
        //     $table->unsignedBigInteger('role_id')->nullable();

        //     // Foreign keys
        //     $table->foreign('activity_id')
        //         ->references('id')
        //         ->on('activities')
        //         ->onDelete('cascade');

        //     $table->foreign('user_id')
        //         ->references('id')
        //         ->on('users')
        //         ->onDelete('cascade');

        //     $table->foreign('role_id')
        //         ->references('id')
        //         ->on('roles')
        //         ->onDelete('set null');

        //     $table->timestamps();
        // });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_user_role');
    }
};
