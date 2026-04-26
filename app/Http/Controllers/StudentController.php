<?php


namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Announcement;
use App\Models\Role;
use App\Models\Section;
use App\Models\SimulationSession;
use App\Models\SimulationSubmission;
use App\Models\StudentQuiz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $preAssessment = auth()->user()->sections;

        $hasCompletedQuiz = StudentQuiz::hasCompleted(auth()->id());

        $sections = Section::whereHas('members', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->with('instructor')
            ->where('is_archived', false)
            ->latest()
            ->get();

        $students = User::where('role', 'student')->get();
        $studentCount = $students->count();

        return view('student.section', compact('sections', 'students', 'studentCount', 'preAssessment', 'hasCompletedQuiz'));
    }

    public function show($section_id)
    {
        $section = Section::findOrFail($section_id);
        
        session(['section_id' => $section_id]);

        $activities = $section->activities()
            ->with('user')
            ->get()
            ->map(function ($item) {
                $item->type = 'activity';
                return $item;
            });

        $announcements = Announcement::where('section_id', $section_id)
            ->with('user')
            ->get()
            ->map(function ($item) {
                $item->type = 'announcement';
                return $item;
            });

        $feed = $activities
            ->merge($announcements)
            ->sortByDesc('created_at')
            ->values();

        return view('student.activity', compact('section', 'feed', 'announcements'));
    }

    public function activityShow($section_id, $activity_id)
    {
        try {
            // Find section
            $section = Section::where('section_id', $section_id)->firstOrFail();

            // Find activity
            $activity = Activity::where('section_id', $section->section_id)
                ->where('activity_id', $activity_id)
                ->firstOrFail();

            // Get students in this section
            $users = User::whereHas('sections', function ($query) use ($section) {
                $query->where('section_members.section_id', $section->section_id);
            })
                ->where('role', 'student')
                ->get();

            // Get roles assigned to the current user for this activity
            $userRoles = Role::whereHas('users', function ($query) use ($activity) {
                $query->where('activity_user_role.user_id', auth()->id())
                    ->where('activity_user_role.activity_id', $activity->activity_id);
            })
                ->get();

            // Get session from the Simulation Session table
            $sessions = SimulationSession::where('user_id', auth()->id())
                ->where('activity_id', $activity->activity_id)
                ->get();

            // Sessions by status
            $gradedSubmissions = SimulationSubmission::where('user_id', auth()->id())
                ->where('status', 'graded')
                ->latest('submitted_at')
                ->get();
            
            $submittedSubmissions = SimulationSubmission::where('user_id', auth()->id())
                ->where('status', 'submitted')
                ->latest('submitted_at')
                ->get();

            $completedSessions = SimulationSession::where('user_id', auth()->id())
                ->where('activity_id', $activity->activity_id)
                ->where('status', 'graded')
                ->pluck('role_name')
                ->toArray();

            $hasSubmitted = SimulationSession::where('user_id', auth()->id())
                ->where('activity_id', $activity->activity_id)
                ->whereIn('status', ['submitted', 'graded'])
                ->exists();
                
            $activityRole = DB::table('activity_roles')
            ->where('id', $activity->role_id)
            ->value('name');

            // Log for debugging
            \Log::info('Activity Show Data', [
                'section_id' => $section->section_id,
                'activity_id' => $activity->activity_id,
                'activity_name' => $activity->name,
                'user_roles_count' => $userRoles->count(),
                'user_roles' => $userRoles->pluck('name')->toArray()
            ]);

            return view(
                'student.activity_details',
                compact(
                    'section',
                    'activity',
                    'users',
                    'userRoles',
                    'completedSessions',
                    'hasSubmitted',
                    'sessions',
                    'gradedSubmissions',
                    'submittedSubmissions',
                    'activityRole'
                )
            );
        } catch (\Exception $e) {
            \Log::error('Error in activityShow', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'section_id' => $section_id,
                'activity_id' => $activity_id,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Error loading activity: ' . $e->getMessage());
        }
    }

    public function studentSection($section_id)
    {
        $section = Section::where('section_id', $section_id)->firstOrFail();
        $activity = Activity::first();

        $users = User::whereHas('sections', function ($query) use ($section) {
            $query->where('section_members.section_id', $section->section_id);
        })
            ->where('role', 'student')
            ->get();

        return view('student.student', compact('section', 'activity', 'users'));
    }


    public function studentGrades()
    {
        $userId = Auth::id();
    
        // Get all section IDs where the student belongs
        $sectionIds = DB::table('section_members')
            ->where('user_id', $userId)
            ->pluck('section_id');
    
        // Paginate ALL created activities in the student's sections
        $activities = Activity::whereIn('section_id', $sectionIds)
            ->latest('created_at')
            ->paginate(7);
    
        // Get session data only for the activities on the current page
        $activityIds = $activities->pluck('activity_id');
    
        $sessions = SimulationSession::with(['activity', 'submission'])
            ->where('user_id', $userId)
            ->whereIn('activity_id', $activityIds)
            ->get()
            ->groupBy('activity_id');
    
        // Transform each activity into a grade row
        $activities->getCollection()->transform(function ($activity) use ($sessions) {
            $activitySessions = $sessions->get($activity->activity_id, collect());
    
            $latestSession = $activitySessions->sortByDesc('updated_at')->first();
            $latestSubmission = $activitySessions
                ->pluck('submission')
                ->filter()
                ->sortByDesc('submitted_at')
                ->first();
    
            $activity->role_names = $activitySessions
                ->pluck('role_name')
                ->filter()
                ->unique()
                ->implode(', ');
    
            $activity->submitted_at_display = $latestSubmission?->submitted_at;
            $activity->grade_status = $latestSubmission?->status
                ?? $latestSession?->status
                ?? 'not_started';
    
            $activity->grade_score = $latestSubmission?->score;
    
            return $activity;
        });
    
        // Stats across ALL created activities, not just submitted ones
        $allActivityIds = Activity::whereIn('section_id', $sectionIds)->pluck('activity_id');
    
        $allSessions = SimulationSession::with('submission')
            ->where('user_id', $userId)
            ->whereIn('activity_id', $allActivityIds)
            ->get()
            ->groupBy('activity_id');
    
        $graded = 0;
        $submitted = 0;
        $pending = 0;
    
        foreach ($allActivityIds as $activityId) {
            $activitySessions = $allSessions->get($activityId, collect());
    
            $latestSession = $activitySessions->sortByDesc('updated_at')->first();
            $latestSubmission = $activitySessions
                ->pluck('submission')
                ->filter()
                ->sortByDesc('submitted_at')
                ->first();
    
            $status = $latestSubmission?->status
                ?? $latestSession?->status
                ?? 'not_started';
    
            if ($status === 'graded') {
                $graded++;
            } elseif ($status === 'submitted') {
                $submitted++;
            } elseif (in_array($status, ['pending', 'in_progress'])) {
                $pending++;
            }
        }
    
        $stats = [
            'graded' => $graded,
            'submitted' => $submitted,
            'pending' => $pending,
        ];
    
        $totalActivities = $allActivityIds->count();
    
        return view('student.grades', [
            'activities' => $activities,
            'stats' => $stats,
            'totalActivities' => $totalActivities,
        ]);
    }

    public function submitQuiz(Request $request)
    {
        // Validate that all questions and student info are provided
        $request->validate([
            'age' => 'required|integer|min:1|max:100',
            'section' => 'required|string|max:255',
            'sex' => 'required|in:Male,Female',
            'question_1' => 'required',
            'question_2' => 'required',
            'question_3' => 'required',
            'question_4' => 'required',
            'question_5' => 'required',
            'question_6' => 'required',
            'question_7' => 'required',
            'question_8' => 'required',
            'question_9' => 'required',
            'question_10' => 'required',
            'question_11' => 'required',
            'question_12' => 'required',
            'question_13' => 'required',
            'question_14' => 'required',
            'question_15' => 'required',
            'question_16' => 'required',
            'question_17' => 'required',
            'question_18' => 'required',
            'question_19' => 'required',
            'question_20' => 'required',
            'question_21' => 'required',
            'question_22' => 'required',
            'question_23' => 'required',
            'question_24' => 'required',
            'question_25' => 'required',
        ]);

        // Check if user already completed the quiz
        if (StudentQuiz::hasCompleted(auth()->id())) {
            return redirect()->back()->with('error', 'You have already completed this quiz.');
        }

        // Correct answers
        $correctAnswers = [
            1 => 'B',
            2 => 'C',
            3 => 'B',
            4 => 'B',
            5 => 'B',
            6 => 'B',
            7 => 'C',
            8 => 'B',
            9 => 'B',
            10 => 'C',
            11 => 'C',
            12 => 'B',
            13 => 'B',
            14 => 'B',
            15 => 'B',
            16 => 'C',
            17 => 'B',
            18 => 'B',
            19 => 'B',
            20 => 'C',
            21 => 'B',
            22 => 'C',
            23 => 'B',
            24 => 'B',
            25 => 'B'
        ];

        // Calculate score and collect answers
        $score = 0;
        $studentAnswers = [];

        foreach ($correctAnswers as $questionId => $correctAnswer) {
            $studentAnswer = $request->input("question_{$questionId}");
            $studentAnswers[$questionId] = [
                'answer' => $studentAnswer,
                'correct' => $correctAnswer,
                'is_correct' => $studentAnswer === $correctAnswer
            ];

            if ($studentAnswer === $correctAnswer) {
                $score++;
            }
        }

        // Save to database including age, section, and sex
        StudentQuiz::create([
            'user_id' => auth()->id(),
            'quiz_type' => 'pre-assessment',
            'age' => $request->input('age'),
            'section' => $request->input('section'),
            'sex' => $request->input('sex'),
            'score' => $score,
            'answers' => $studentAnswers,
            'completed_at' => now()
        ]);

        $percentage = round(($score / 25) * 100, 2);

        return redirect()->back()->with('success', "Quiz completed! Your score: {$score}/25 ({$percentage}%)");
    }

    // Optional: View quiz results
    public function viewQuizResults()
    {
        $quiz = StudentQuiz::where('user_id', auth()->id())
            ->where('quiz_type', 'pre-assessment')
            ->first();

        if (!$quiz) {
            return redirect()->back()->with('error', 'No quiz results found.');
        }

        return view('student.quiz-results', compact('quiz'));
    }
}
