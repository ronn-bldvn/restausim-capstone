<?php

namespace App\Models;

use App\BelongsToActivity;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use BelongsToActivity;
    protected $fillable = [
        'order_id',
        'cashier_id',
        'payment_method',
        'amount',
        'cash_recieved',
        'card_type',
        'bank',
        'authorization_code',
        'reference_number',
        'status',
        'paid_at',
    ];

    public function order(){
        return $this->belongsTo(
            Order::class,
            'order_id',
            'id'
        );
    }

    public function cashier(){
        return $this->belongsTo(
            User::class,
            'cashier_id',
            'id'
        );
    }

    public function item_orders(){
        return $this->belongsToMany(
            ItemOrder::class,
            'item_order_payments',
            'payment_id',
            'item_order_id'
        );
    }
}
