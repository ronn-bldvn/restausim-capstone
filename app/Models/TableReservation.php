<?php

namespace App\Models;

use App\BelongsToActivity;
use Illuminate\Database\Eloquent\Model;

class TableReservation extends Model
{
    use BelongsToActivity;

    protected $table = 'table_reservation';

    protected $fillable = [
        'table_id',
        'reservee_name',
        'reservation_time',
    ];

    protected $casts = [
        'reservation_time' => 'datetime',
    ];

    public function table()
    {
        return $this->belongsTo(
            Table::class,
            'table_id',
            'id'
        );
    }
}
