<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'floor_plan_id',
        'name',
        'capacity',
        'shape',
        'x',
        'y',
        'width',
        'height',
        'rotation',
        'status',
        'table_code',
    ];

    public function checkIfOrdersAreComplete(){
        $status = true;
        $order = Order::where('status', 'preparing')->first();
        if($order !== null){
            foreach($order->itemOrders as $item){
                if(!in_array($item->status, ['completed', 'served', 'cancelled'])){
                    $status = false;
                }
            }
        }
        return $status;
    }

    public function floorplan()
    {
        return $this->belongsTo(
            FloorPlan::class,
            'floor_plan_id',
            'id'
        );
    }

    public function orders()
    {
        return $this->hasMany(
            Order::class,
            'table_id',
            'id'
        );
    }

    public function combinedItems()
    {
        return $this->hasMany(
            CombinedTableItem::class,
            'table_id',
            'id'
        );
    }

    public function combinedTables()
    {
        return $this->belongsToMany(
            CombinedTable::class,
            'combined_table_items',
            'table_id',
            'combined_table_id'
        );
    }
}