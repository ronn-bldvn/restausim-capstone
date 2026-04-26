<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inventory_id');
            $table->enum('type', ['in', 'out', 'adjust', 'waste', 'void']);

            $table->decimal('quantity', 12, 4);
            $table->decimal('unit_cost', 12, 4)->nullable();

            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('item_order_id')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('inventory_id')
                  ->references('id')
                  ->on('inventories')
                  ->onDelete('cascade');

            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');

            $table->foreign('item_order_id')
                  ->references('id')
                  ->on('item_orders')
                  ->onDelete('cascade');

            // Indexes for reporting performance
            $table->index(['inventory_id', 'type']);
            $table->index(['order_id']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
