<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('activities', function (Blueprint $table) {
        $table->unsignedBigInteger('section_id')->after('activity_id');

        // Foreign key constraint (optional but recommended)
        $table->foreign('section_id')->references('section_id')->on('section')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('activities', function (Blueprint $table) {
        $table->dropForeign(['section_id']);
        $table->dropColumn('section_id');
    });
}

};
