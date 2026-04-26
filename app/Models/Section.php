<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Section extends Model
{
    protected $table = 'section';

    protected $fillable = [
        'section_name',
        'class_name',
        'class_code',
        'user_id',
        'share_code',
        'is_archived',
    ];

    protected $primaryKey = 'section_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $casts = ['is_archived' => 'boolean'];

    // Relationship to the instructor (owner of the section)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Alternative naming for clarity
    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'section_id', 'section_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'section_members', 'section_id', 'user_id')
                    ->withTimestamps();
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'section_members', 'section_id', 'user_id')
                    ->withTimestamps()
                    ->withPivot('joined_at');
    }

    // public function invitations()
    // {
    //     return $this->hasMany(SectionInvitation::class, 'section_id', 'section_id');
    // }


    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'section_members',
            'section_id',
            'user_id'
            )->where('users.role', 'student')
            ->withTimestamps()
            ->withPivot('joined_at');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($section) {
            if (empty($section->share_code)) {
                $section->share_code = static::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode()
    {
        do {
            $code = 'RST' . strtoupper(Str::random(5));
        } while (static::where('share_code', $code)->exists());

        return $code;
    }

    public function getShareLinkAttribute()
    {
        return route('section.join', ['code' => $this->share_code]);
    }

     public static function generateInviteCode()
    {
        do {
            $code = strtoupper(Str::random(5));
        } while (self::where('invite_code', $code)->exists());

        return $code;
    }

    public static function generateShareCode()
    {
        do {
            $code = 'RST-' . strtoupper(substr(Str::random(5), 0, 5));
        } while (self::where('share_code', $code)->exists());

        return $code;
    }
}
