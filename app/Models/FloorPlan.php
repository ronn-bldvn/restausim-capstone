<?php

namespace App\Models;

use App\BelongsToActivity;
use Illuminate\Database\Eloquent\Model;

class FloorPlan extends Model
{
    use BelongsToActivity;
    protected $fillable = [
        'name',
    ];

    public function tables(){
        return $this->hasMany(
            Table::class,
            'floor_plan_id',
            'id'
        );
    }
}
