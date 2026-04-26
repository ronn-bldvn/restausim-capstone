<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('simulation_submissions', function (Blueprint $table) {
            $table->string('simulation_name')->nullable()->after('batch_code');
        });
    }

    public function down(): void
    {
        Schema::table('simulation_submissions', function (Blueprint $table) {
            $table->dropColumn('simulation_name');
        });
    }
};