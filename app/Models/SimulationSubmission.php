<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimulationSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'batch_code',
        'simulation_name',
        'submitted_at',
        'status',
        'score',
        'feedback',
        'summary',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'summary' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sessions()
    {
        return $this->hasMany(SimulationSession::class, 'submission_id');
    }

    public function actions()
    {
        return $this->hasMany(SimulationAction::class, 'submission_id');
    }

    public function getRoleNameAttribute()
    {
        return $this->sessions->pluck('role_name')->unique()->implode(', ');
    }

    public function getSessionDataAttribute()
    {
        return $this->summary ?? [];
    }
}