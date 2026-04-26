<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('simulation_actions', function (Blueprint $table) {
            $table->unsignedBigInteger('submission_id')->nullable()->after('session_id');

            $table->foreign('submission_id')
                ->references('id')
                ->on('simulation_submissions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('simulation_actions', function (Blueprint $table) {
            $table->dropForeign(['submission_id']);
            $table->dropColumn('submission_id');
        });
    }
};