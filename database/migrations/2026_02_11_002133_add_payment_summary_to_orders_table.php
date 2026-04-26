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
        Schema::table('orders', function (Blueprint $table) {
            $table->after('status', function ($table){
                $table->decimal('subtotal_amount', 10, 2);
                $table->decimal('total_discount_amount', 10, 2);
                
                $table->enum('discount_type', ['senior', 'pwd', 'promo', 'voucher', 'manual', 'none'])->default('none');
                $table->decimal('order_discount_percentage', 5, 2)->nullable();
                $table->decimal('order_discount_amount', 10, 2)->nullable();
                $table->text('notes')->nullable();

                $table->decimal('service_charge_rate', 5, 2);
                $table->decimal('service_charge_amount', 10, 2);
                $table->decimal('service_charge_vat_amount', 10, 2);
                
                $table->decimal('total_vat_amount', 10, 2);
                $table->decimal('total_amount', 10, 2);

                $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
                $table->timestamp('paid_at')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal_amount', 'total_discount_amount', 'discount_type', 'order_discount_percentage', 'order_discount_amount', 'notes', 'service_charge_rate', 'service_charge_amount', 'service_charge_vat_amount', 'total_vat_amount', 'total_amount', 'payment_status', 'paid_at']);
        });
    }
};
