<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShareCodeToSectionTable extends Migration
{
    public function up()
    {
        // Schema::table('section', function (Blueprint $table) {
        //     $table->string('share_code', 8)->unique()->nullable()->after('class_name');
        // });

        // Schema::create('section_members', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('section_id');
        //     $table->unsignedBigInteger('user_id');
        //     $table->timestamp('joined_at')->useCurrent();

        //     $table->foreign('section_id')->references('section_id')->on('section')->onDelete('cascade');
        //     $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        //     $table->unique(['section_id', 'user_id']);
        // });
    }

    public function down()
    {
        Schema::dropIfExists('section_members');
        Schema::table('section', function (Blueprint $table) {
            $table->dropColumn('share_code');
        });
    }
}
