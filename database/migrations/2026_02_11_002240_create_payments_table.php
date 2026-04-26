<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('cashier_id')->constrained('users')->cascadeOnDelete();
            $table->enum('payment_method', ['cash', 'debit', 'credit']);
            $table->decimal('amount', 10, 2);
            $table->decimal('cash_recieved', 10, 2)->nullable();
            $table->enum('card_type', ['visa', 'mastercard', 'jcb', 'unionpay'])->nullable();
            $table->enum('bank', ['bdo', 'bpi', 'metrobank', 'landbank'])->nullable();
            $table->string('authorization_code', 50)->nullable();
            $table->string('reference_number', 50)->nullable()->unique();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->dateTime('paid_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
