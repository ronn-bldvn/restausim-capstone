<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimulationSession extends Model
{
    protected $fillable = [
        'activity_id',
        'submission_id',
        'user_id',
        'role_name',
        'started_at',
        'submitted_at',
        'status',
        'score',
        'feedback',
        'session_data',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'session_data' => 'array',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'activity_id');
    }

    public function submission()
    {
        return $this->belongsTo(SimulationSubmission::class, 'submission_id');
    }

    public function logs()
    {
        return $this->hasMany(ActivityLog::class, 'simulation_session_id');
    }

    public function actions()
    {
        return $this->hasMany(SimulationAction::class, 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}