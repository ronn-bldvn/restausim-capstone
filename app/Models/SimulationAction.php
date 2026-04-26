<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimulationAction extends Model
{
    protected $fillable = [
        'session_id',
        'submission_id',
        'action_type',
        'action_data',
        'timestamp',
        'is_correct',
        'points_earned',
    ];

    protected $casts = [
        'action_data' => 'array',
        'timestamp' => 'datetime',
        'is_correct' => 'boolean',
        'points_earned' => 'decimal:2',
    ];

    public function session()
    {
        return $this->belongsTo(SimulationSession::class, 'session_id');
    }

    public function submission()
    {
        return $this->belongsTo(SimulationSubmission::class, 'submission_id');
    }
}