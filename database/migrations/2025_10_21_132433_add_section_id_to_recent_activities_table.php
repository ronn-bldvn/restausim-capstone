<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Schema::table('recent_activities', function (Blueprint $table) {
        //     $table->unsignedBigInteger('section_id')->nullable()->after('user_id');

        //     // Optional: Add foreign key constraint
        //     // $table->foreign('section_id')->references('section_id')->on('sections')->onDelete('cascade');
        // });
    }

    public function down()
    {
        // Schema::table('recent_activities', function (Blueprint $table) {
        //     $table->dropForeign(['section_id']);
        //     $table->dropColumn('section_id');
        // });
    }
};
