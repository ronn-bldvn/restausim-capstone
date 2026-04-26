<?php

namespace App\Models;

use App\BelongsToActivity;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use BelongsToActivity;
    protected $fillable = [
        'name',
        'type',
        'discount_type',
        'discount_value',
        'is_vat_exempt',
    ];

    public function order_discounts(){
        return $this->belongsToMany(
            Order::class,
            'order_discounts',
            'discount_id',
            'order_id'
        );
    }

    public function menu_item_discounts(){
        return $this->belongsToMany(
            MenuItem::class,
            'menu_item_discounts',
            'discount_id',
            'menu_item_id'
        );
    }

    public function item_order_discounts(){
        return $this->belongsToMany(
            ItemOrder::class,
            'item_order_discounts',
            'discount_id',
            'item_order_id'
        );
    }
}
