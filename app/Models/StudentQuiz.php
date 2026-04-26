<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentQuiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'age',
        'sex',
        'section',
        'quiz_type',
        'score',
        'answers',
        'completed_at'
    ];

    protected $casts = [
        'answers' => 'array',
        'completed_at' => 'datetime'
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Check if user has completed a specific quiz type
    public static function hasCompleted($userId, $quizType = 'pre-assessment')
    {
        return self::where('user_id', $userId)
            ->where('quiz_type', $quizType)
            ->exists();
    }

    // Get user's quiz score
    public static function getScore($userId, $quizType = 'pre-assessment')
    {
        $quiz = self::where('user_id', $userId)
            ->where('quiz_type', $quizType)
            ->first();

        return $quiz ? $quiz->score : null;
    }
}
