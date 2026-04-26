<?php

namespace App\Models;

use App\BelongsToActivity;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use BelongsToActivity;
    protected $fillable = [
        'table_id',
        'combined_table_id',
        'type',
        'status',
        'subtotal_amount',
        'total_discount_amount',
        'discount_type',
        'order_discount_percentage',
        'order_discount_amount',
        'notes',
        'service_charge_rate',
        'service_charge_amount',
        'service_charge_vat_amount',
        'total_vat_amount',
        'total_amount',
        'payment_status',
        'paid_at',
        'vat_amount',
        
    ];

    protected $appends = [
        'vat_sales',
        'vat_exempt_sales',
        'total_vat',
        'total_discount',
        'amount_due',
        'subtotal_amount',
    ];

    public function getVatSalesAttribute(){
        $vat_sales = 0;
        foreach($this->items as $item){
            if($item->status === 'completed' && !$item->vat_exempt_due_to_discount){
                $vat_sales += $item->discounted_net * $item->quantity_ordered;
                // dd($vat_sales, $item->discounted_net, $item->quantity_ordered);
            }
        }
        // dd($vat_sales);
        return $vat_sales;
    }

    public function getVatExemptSalesAttribute(){
        $vat_exempt_sales = 0;
        foreach($this->items as $item){
            if($item->status === 'completed' && $item->vat_exempt_due_to_discount){
                $vat_exempt_sales += $item->net_amount;
            }
        }
        return $vat_exempt_sales;
    }

    public function getTotalVatAttribute(){
        $vat = 0;
        foreach($this->items as $item){
            if($item->status === 'completed' && !$item->vat_exempt_due_to_discount){
                $vat += $item->calculateVatAmount($item->final_unit_price) * $item->quantity_ordered;
            }
        }
        return $vat;
    }

    public function getTotalDiscountAttribute(){
        $discounts = 0;
        foreach($this->items as $item){
            if($item->status === 'completed'){
                $discounts += (int) $item->discount_amount ? $item->discount_amount : $item->order_discount_amount;
            }
        }
        return $discounts;
    }

    public function getAmountDueAttribute(){
        $amount_due = 0;
        foreach($this->items as $item){
            if($item->status === 'completed'){
                $amount_due += $item->line_gross_amount;
                // dd($amount_due);
            }
        }
        return $amount_due;
    }

    public function getSubtotalAmountAttribute(){
        $subtotal = 0;
        foreach($this->items as $item){
            if($item->status === 'completed' && !$item->vat_exempt_due_to_discount && $item->discount_type === 'none'){
                $subtotal += $item->final_unit_price * $item->quantity_ordered;
            }
        }
        return $subtotal;
    }

    // public function calculateOrderDiscountAmount($discountPercentage){
    //     $discountPercentage = $discountPercentage / 100;
    //     return $this->subtotal_amount * $discountPercentage;
    // }

    public function calculateItemOrderDiscount($net_price, $amount, $type){
        // dd($net_price, $amount, $type);
        // dd($this->calculateOrderDiscountAmount($amount));

        // dd($this->subtotal_amount);

        $discountPercentage = $amount / 100;
        $discount_amount = $this->subtotal_amount * $discountPercentage;
        // dd($discount_amount);
        if($type === 'percentage'){
            return ($net_price / $this->subtotal_amount) * $discount_amount;
        }
        elseif($type === 'flat'){
            return ($net_price / $this->subtotal_amount) * $amount;
        }
    }

    public function checkPayment(){
        $total_payment_amount = 0;
        foreach($this->payments as $payment){
            $total_payment_amount += $payment->amount;
        }

        return ((int) $total_payment_amount === (int) $this->total_amount) ? true : false;
    }

    public function items(){
        return $this->hasMany(
            ItemOrder::class,
            'order_id',
            'id'
        );
    }

    public function table(){
        return $this->belongsTo(
            Table::class,
            'table_id',
            'id'
        );
    }

    public function combinedTable(){
        return $this->belongsTo(
            CombinedTable::class,
            'combined_table_id',
            'id'
        );
    }

    public function payments(){
        return $this->hasMany(
            Payment::class,
            'order_id',
            'id'
        );
    }

    public function itemOrders()
    {
        return $this->hasMany(ItemOrder::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
