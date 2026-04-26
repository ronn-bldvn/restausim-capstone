<?php

namespace App\Models;

use App\BelongsToActivity;
use Illuminate\Database\Eloquent\Model;

class CombinedTable extends Model
{
    use BelongsToActivity;
    protected $fillable = [
        'floor_plan_id',
        'total_capacity',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(
            CombinedTableItem::class,
            'combined_table_id',
            'id'
        );
    }

    public function tables()
    {
        return $this->belongsToMany(
            Table::class,
            'combined_table_items',
            'combined_table_id',
            'table_id'
        );
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
            'combined_table_id',
            'id'
        );
    }
}
