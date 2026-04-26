<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Activity extends Model
{
    protected $table = 'activities';

    protected $fillable = [
        'name',
        'description',
        'grades',
        'due_date',
        'role_id',
        'section_id',
        'user_id',
    ];

    protected $casts = [
        'due_date' => 'datetime',  // ✅ Fixed - removed the format
    ];

    protected $primaryKey = 'activity_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public function getFormattedDueDateAttribute()
    {
        return $this->due_date
            ? $this->due_date->setTimezone('Asia/Manila')->format('F j, Y g:i A')
            : null;
    }

    public function hasDueDate(){
        return !is_null($this->due_date);
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function isFaculty()
    {
        return $this->role === 'faculty';
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'activity_user_role', 'activity_id', 'user_id')
                    ->withPivot('role_id')
                    ->withTimestamps();
    }

    public function simulationSessions()
    {
        return $this->hasMany(SimulationSession::class, 'activity_id');
    }

    public function students()
    {
        return $this->hasManyThrough(
            User::class,
            SectionMember::class,
            'section_id',      // Foreign key on section_members table
            'id',              // Foreign key on users table
            'section_id',      // Local key on activities table
            'user_id'          // Local key on section_members table
        );
    }

    public function role(){
        return $this->belongsTo(
            Role::class,
            'role_id',
            'id'
        );
    }
}
