<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOrder extends Model
{
    protected $fillable = [
        'order_id',
        'menu_item_id',
        'status',
        'quantity_ordered',
        'price_at_sale',
        'vat_rate',
        'vat_amount',
        'discount_type', // o
        'discount_percentage',
        'discount_amount',
        // 'order_discount_amount',  r
        'vat_exempt_due_to_discount',
        'final_unit_price', // rename: final_unit_price
        'notes',
        'net_amount'
    ];

    protected $appends = [
        'vat_factor',
        'vat_amount',
        'net_price', // rename: net_price
        'line_gross_amount', // rename: line_gross_amount
        'discounted_net'
        // 'base_net_price'
    ];

    public function getVatFactorAttribute(){
        return $this->vat_rate / 100;
    }

    public function getVatAmountAttribute(){
        return $this->price_at_sale * $this->vat_factor / (1 + $this->vat_factor);
    }
    // BEFORE VAT
    public function getNetPriceAttribute(){
        return $this->price_at_sale / (1 + $this->vat_factor);
    }
    // PRODUCT OF GROSS AND QUANTITY
    public function getLineGrossAmountAttribute(){
        return $this->final_unit_price * $this->quantity_ordered;
    }
    public function getDiscountedNetAttribute(){
        return $this->final_unit_price / (1 + $this->vat_factor);
    }
    // PRICE BEFORE VAT

    // HOW MUCH IS THE DISCOUNT
    public function calculateDiscountAmount($discountPercentage){
        return ($this->net_price) * ($discountPercentage / 100);
    }

    // DIFFERENCE BETWEEN VAT EXCLUSIVE PRICE AND DISCOUNT AMOUNT
    public function calculateDiscountedNetAmount($discountPercentage){
        return $this->net_price - $this->calculateDiscountAmount($discountPercentage);
    }

    // FINAL PRICE OF AN ITEM
    public function calculateDiscountedGrossAmount($discountPercentage, $is_vat_exempt){
        $vat_rate = $is_vat_exempt ? 0 : $this->vat_factor;
        $vat_amount = $this->calculateVatAmount($this->calculateDiscountedNetAmount($discountPercentage));
        return $this->calculateDiscountedNetAmount($discountPercentage) + $vat_amount;
    }

    // SUM OF QUANTITY OF ITEMS
    public function calculateFinalUnitPrice($discountPercentage, $is_vat_exempt){
        return $this->calculateDiscountedGrossAmount($discountPercentage, $is_vat_exempt) * $this->quantity_ordered;
    }

    public function calculateVatAmount($amount){
        return $amount * $this->vat_factor / (1 + $this->vat_factor);
    }

    public function order(){
        return $this->belongsTo(
            Order::class,
            'order_id',
            'id'
        );
    }

    public function item(){
        return $this->belongsTo(
            MenuItem::class,
            'menu_item_id',
            'id'
        );
    }

    public function customizations(){
        return $this->hasMany(
            ItemOrderCustomization::class,
            'item_order_id',
            'id'
        );
    }

    public function item_order_discounts(){
        return $this->belongsToMany(
            Discount::class,
            'item_order_discounts',
            'item_order_id',
            'discount_id'
        );
    }

    public function payments(){
        return $this->belongsToMany(
            Payment::class,
            'item_order_payments',
            'item_order_id',
            'payment_id'
        );
    }
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
