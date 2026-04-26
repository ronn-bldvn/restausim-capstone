<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tables') && Schema::hasColumn('tables', 'floorplan_id') && !Schema::hasColumn('tables', 'floor_plan_id')) {
            Schema::table('tables', function (Blueprint $table) {
                $table->renameColumn('floorplan_id', 'floor_plan_id');
            });
        }

        if (Schema::hasTable('combined_tables') && Schema::hasColumn('combined_tables', 'floorplan_id') && !Schema::hasColumn('combined_tables', 'floor_plan_id')) {
            Schema::table('combined_tables', function (Blueprint $table) {
                $table->renameColumn('floorplan_id', 'floor_plan_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tables') && Schema::hasColumn('tables', 'floor_plan_id')) {
            Schema::table('tables', function (Blueprint $table) {
                $table->renameColumn('floor_plan_id', 'floorplan_id');
            });
        }

        if (Schema::hasTable('combined_tables') && Schema::hasColumn('combined_tables', 'floor_plan_id')) {
            Schema::table('combined_tables', function (Blueprint $table) {
                $table->renameColumn('floor_plan_id', 'floorplan_id');
            });
        }
    }
};

