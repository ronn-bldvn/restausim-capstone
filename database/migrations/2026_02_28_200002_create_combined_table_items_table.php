<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('combined_table_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combined_table_id')->constrained('combined_tables')->cascadeOnDelete();
            $table->foreignId('table_id')->constrained('tables')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['combined_table_id', 'table_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combined_table_items');
    }
};

