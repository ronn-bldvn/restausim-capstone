<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'inventory_id', 'type', 'quantity', 'unit_cost',
        'order_id', 'item_order_id',
    ];

    public function inventory() { return $this->belongsTo(Inventory::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function itemOrder() { return $this->belongsTo(ItemOrder::class); }
    
}
