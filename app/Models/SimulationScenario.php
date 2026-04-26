<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimulationScenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'scenario_type',
        'name',
        'description',
        'parameters',
        'grading_rubric'
    ];

    protected $casts = [
        'parameters' => 'array',
        'grading_rubric' => 'array'
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
