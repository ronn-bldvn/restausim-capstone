<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'simulation_session_id',
        'role_name',
        'action',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function simulationSession()
    {
        return $this->belongsTo(SimulationSession::class, 'simulation_session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}