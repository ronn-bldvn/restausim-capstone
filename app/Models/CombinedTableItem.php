<?php

namespace App\Models;

use App\BelongsToActivity;
use Illuminate\Database\Eloquent\Model;

class CombinedTableItem extends Model
{
    use BelongsToActivity;
    protected $fillable = [
        'combined_table_id',
        'table_id',
    ];

    public function combinedTable()
    {
        return $this->belongsTo(
            CombinedTable::class,
            'combined_table_id',
            'id'
        );
    }

    public function table()
    {
        return $this->belongsTo(
            Table::class,
            'table_id',
            'id'
        );
    }
}

