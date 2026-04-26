<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'profile_image',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'section_members', 'user_id', 'section_id')->withTimestamps();
    }

    // User.php model

    public function getMaskedPasswordAttribute()
    {
        // Show first 3 characters, mask the rest
        $length = strlen($this->password);
        $firstFew = substr($this->password, 0, 3);
        return $firstFew . str_repeat('*', max(0, $length - 3));
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_user_role', 'user_id', 'activity_id')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function userRoles()
    {
        return $this->belongsToMany(Role::class, 'activity_user_role', 'user_id', 'role_id')
            ->withPivot('activity_id')
            ->withTimestamps();
    }

    public function simulationSessions()
    {
        return $this->hasMany(SimulationSession::class);
    }

    public function sectionMembers()
    {
        return $this->hasMany(SectionMember::class, 'user_id');
    }

    public function quizzes()
    {
        return $this->hasMany(StudentQuiz::class);
    }

    public function hasCompletedQuiz($quizType = 'pre-assessment')
    {
        return $this->quizzes()->where('quiz_type', $quizType)->exists();
    }

    public function getQuizScore($quizType = 'pre-assessment')
    {
        $quiz = $this->quizzes()->where('quiz_type', $quizType)->first();
        return $quiz ? $quiz->score : null;
    }
}
