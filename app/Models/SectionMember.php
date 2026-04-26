<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionMember extends Model
{
    protected $table = 'section_members';

    protected $fillable = [
        'section_id',
        'user_id',
        'joined_at',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'section_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
