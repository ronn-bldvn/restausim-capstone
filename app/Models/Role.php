<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    // A role can belong to many users (through the pivot table)
    public function users()
    {
        return $this->belongsToMany(User::class, 'activity_user_role')
                    ->withPivot('activity_id')
                    ->withTimestamps();
    }

    // A role can also belong to many activities (through the pivot)
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_user_role')
                    ->withPivot('user_id')
                    ->withTimestamps();
    }
}
