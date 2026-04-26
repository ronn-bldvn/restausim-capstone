<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailInvites extends Model
{
    protected $fillable = [
        'section_id',
        'email',
        'token',
        'expires_at'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'section_id');
    }
}
