<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Announcement;
use App\Models\Role;
use App\Models\Section;
use App\Models\SectionMember;
use App\Models\SimulationSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArchiveController extends Controller
{
    public function archivedSection()
    {
        $facultyId = auth()->id();

        // Get sections created by this faculty
        $sections = Section::where('user_id', $facultyId)
            ->where('is_archived', true)
            ->get();

        // Collect all section IDs for this faculty
        $sectionIds = $sections->pluck('section_id');

        // Compute section-level counts
        foreach ($sections as $section) {
            // Existing counts
            $section->totalStudents = SectionMember::where('section_id', $section->section_id)->count();
            $section->totalActivity = Activity::where('section_id', $section->section_id)->count();

            // Get all activities for this section
            $activityIds = Activity::where('section_id', $section->section_id)->pluck('activity_id');

            $section->totalStudents = SectionMember::where('section_id', $section->section_id)->count();
            $section->totalActivity = Activity::where('section_id', $section->section_id)->count();
        }

        // Get the total archived sections
        $totalArchivedSections = Section::where('user_id', $facultyId)
            ->where('is_archived', true)
            ->count();

        // Get students who are enrolled in those sections only
        $students = User::where('role', 'student')
            ->whereIn('id', function ($query) use ($sectionIds) {
                $query->select('user_id')
                    ->from('section_members')
                    ->whereIn('section_id', $sectionIds);
            })
            ->get();

        $studentCount = $students->count();

        return view('faculty.archivedSections', compact('sections', 'studentCount', 'students', 'totalArchivedSections'));
    }

    public function archivedActivities($section_id)
    {
        $section = Section::findOrFail($section_id);

        $activities = Activity::where('section_id', $section_id)
            ->withCount('simulationSessions')
            ->where('is_archived', true)
            ->with('user')
            ->get()
            ->map(function ($activity) {
                if ($activity->due_date) {
                    $dueDate = Carbon::parse($activity->due_date)->timezone('Asia/Manila');

                    $activity->formatted_due_date_card  = $dueDate->format('M d - g:i a');
                    $activity->formatted_due_date_long  = $dueDate->format('F j, Y - g:i a');
                    $activity->formatted_due_date_value = $dueDate->format('Y-m-d\TH:i');
                } else {
                    $activity->formatted_due_date_card  = 'No due date';
                    $activity->formatted_due_date_long  = 'No Due Date';
                    $activity->formatted_due_date_value = '';
                }
                return $activity;
            });

        $totalActivity = $activities->count();

        $totalStudents = SectionMember::where('section_id', $section_id)->count();

        return view('faculty.archivedActivity', compact('section', 'activities', 'totalActivity', 'totalStudents'));
    }

    public function activityShow($section_id, $activity_id)
    {
        $section = Section::where('section_id', $section_id)->firstOrFail();

        $activity = Activity::where('section_id', $section->section_id)
            ->where('is_archived', true)
            ->where('activity_id', $activity_id)
            ->firstOrFail();

        $users = User::where('section_id', $section->section_id)
            ->where('role', 'student')
            ->get();

        $submissions = SimulationSession::where('activity_id', $activity_id)
            ->where('status', 'submitted')
            ->with('user')
            ->get();

        return view('faculty.archivedActivityDetails', compact('section', 'activity', 'users', 'section_id', 'submissions'));
    }

    public function getArchivedActivities($section_id)
    {
        try {
            $section = Section::where('section_id', $section_id)
                ->where('is_archived', true)
                ->firstOrFail();

            if ($section->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Get archived activities
            $activities = Activity::where('section_id', $section_id)
                ->where('is_archived', true)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Count all students (including archived)
            $totalStudents = SectionMember::where('section_id', $section_id)
                ->count();

            $formattedActivities = $activities->map(function ($activity) use ($totalStudents) {
                // Count submissions (submitted and graded)
                $submittedCount = DB::table('simulation_sessions')
                    ->where('activity_id', $activity->activity_id)
                    ->whereIn('status', ['submitted', 'graded'])
                    ->count();

                return [
                    'id' => $activity->activity_id,
                    'title' => $activity->name ?? 'Untitled Activity',
                    'description' => $activity->description ?? 'No description',
                    'due_date' => $activity->due_date ? date('M d, Y', strtotime($activity->due_date)) : 'No due date',
                    'created_at' => $activity->created_at ? $activity->created_at->format('M d, Y') : 'N/A',
                    'submitted_count' => $submittedCount,
                    'total_students' => $totalStudents,
                ];
            });

            return response()->json([
                'success' => true,
                'activities' => $formattedActivities
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading archived activities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load activities: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getArchivedStudents($section_id)
    {
        try {
            $section = Section::where('section_id', $section_id)
                ->where('is_archived', true)
                ->firstOrFail();

            if ($section->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Get all students (including archived members)
            $students = SectionMember::where('section_members.section_id', $section_id)
                ->join('users', 'section_members.user_id', '=', 'users.id')
                ->leftJoin('activity_user_role', function ($join) use ($section_id) {
                    $join->on('users.id', '=', 'activity_user_role.user_id')
                        ->whereIn('activity_user_role.activity_id', function ($query) use ($section_id) {
                            $query->select('activity_id')
                                ->from('activities')
                                ->where('section_id', $section_id)
                                ->where('is_archived', true);
                        });
                })
                ->leftJoin('roles', 'activity_user_role.role_id', '=', 'roles.id')
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'section_members.joined_at',
                    DB::raw('GROUP_CONCAT(DISTINCT roles.name SEPARATOR ", ") as simulation_roles')
                )
                ->groupBy('users.id', 'users.name', 'users.email', 'section_members.joined_at')
                ->orderBy('users.name', 'asc')
                ->take(5)
                ->get();

            return response()->json([
                'success' => true,
                'students' => $students
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading archived students: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load students: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getArchivedSubmissions($activity_id)
    {
        try {
            // Find the archived activity
            $activity = Activity::where('activity_id', $activity_id)
                ->where('is_archived', true)
                ->firstOrFail();

            // Verify ownership
            $section = Section::where('section_id', $activity->section_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Get all students in the section (including archived members)
            $allStudents = SectionMember::where('section_members.section_id', $activity->section_id)
                ->join('users', 'section_members.user_id', '=', 'users.id')
                ->select('users.id', 'users.name', 'users.email')
                ->get();

            $totalStudents = $allStudents->count();

            // Get all simulation sessions for this activity
            $sessions = SimulationSession::where('activity_id', $activity_id)
                ->get()
                ->groupBy('user_id');

            // Initialize arrays
            $assigned = [];
            $turnedin = [];
            $graded = [];

            foreach ($allStudents as $student) {
                $studentSessions = $sessions->get($student->id);

                if (!$studentSessions || $studentSessions->isEmpty()) {
                    // No submission - Assigned
                    $assigned[] = [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'submitted_at' => null,
                    ];
                } else {
                    // Check statuses
                    $hasGraded = $studentSessions->contains('status', 'graded');
                    $hasSubmitted = $studentSessions->contains('status', 'submitted');

                    if ($hasGraded) {
                        // Calculate average score for graded sessions
                        $gradedSessions = $studentSessions->where('status', 'graded');
                        $avgScore = $gradedSessions->avg('score');

                        $graded[] = [
                            'id' => $student->id,
                            'name' => $student->name,
                            'email' => $student->email,
                            'submitted_at' => $gradedSessions->first()->updated_at->format('M d, Y g:i A'),
                            'score' => round($avgScore, 2),
                        ];
                    } elseif ($hasSubmitted) {
                        // Submitted but not graded
                        $submittedSession = $studentSessions->where('status', 'submitted')->first();

                        $turnedin[] = [
                            'id' => $student->id,
                            'name' => $student->name,
                            'email' => $student->email,
                            'submitted_at' => $submittedSession->updated_at->format('M d, Y g:i A'),
                        ];
                    } else {
                        // Has sessions but not submitted (in_progress)
                        $assigned[] = [
                            'id' => $student->id,
                            'name' => $student->name,
                            'email' => $student->email,
                            'submitted_at' => null,
                        ];
                    }
                }
            }

            // Calculate counts
            $submittedCount = count($turnedin) + count($graded);
            $gradedCount = count($graded);
            $pendingCount = count($turnedin);

            return response()->json([
                'success' => true,
                'counts' => [
                    'total_students' => $totalStudents,
                    'submitted' => $submittedCount,
                    'graded' => $gradedCount,
                    'pending' => $pendingCount,
                ],
                'lists' => [
                    'assigned' => $assigned,
                    'turnedin' => $turnedin,
                    'graded' => $graded,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching archived submissions', [
                'activity_id' => $activity_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load submissions: ' . $e->getMessage()
            ], 500);
        }
    }

    public function archivedAnnouncements($section_id)
    {
        $section = Section::findOrFail($section_id);

        $announcements = Announcement::where('section_id', $section_id)
            ->where('is_archived', true)
            ->with('user')
            ->latest()
            ->get();

        return view('faculty.archivedAnnouncements', compact('section', 'announcements', 'section_id'));
    }

    public function showStudents($sectionId, $activityId)
    {
        $section = Section::findOrFail($sectionId);
        $activity = Activity::findOrFail($activityId);
        $roles = Role::all();

        // Get all students in this section
        $users = $section->students()
            ->wherePivot('is_archived', true)
            ->with('sections')
            ->get();

        return view('faculty.archivedStudentSection', compact('section', 'activity', 'users', 'roles'));
    }

    public function getSubmissions($activityId)
    {
        $activity = Activity::where('activity_id', $activityId)
            ->firstOrFail();

        // Verify faculty owns this activity
        if ($activity->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $submissions = SimulationSession::where('activity_id', $activityId)
            ->with(['user', 'actions'])
            ->where('status', '!=', 'in_progress')
            ->whereHas('user', function ($query) use ($activity) {
                $query->whereHas('sectionMembers', function ($q) use ($activity) {
                    $q->where('section_id', $activity->section_id)
                        ->where('is_archived', 1);
                });
            })
            ->orderBy('submitted_at', 'desc')
            ->get();

        return response()->json($submissions);
    }

    public function reviewSubmission($sessionId)
    {
        $session = SimulationSession::with(['user', 'actions', 'activity', 'activity.section'])
            ->findOrFail($sessionId);

        // Verify faculty owns this activity
        if ($session->activity->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Verify the user is archived in the section
        $isArchived = $session->user->sectionMembers()
            ->where('section_id', $session->activity->section_id)
            ->where('is_archived', 1)
            ->exists();

        if (!$isArchived) {
            abort(404, 'This submission is not from an archived user');
        }

        return view('faculty.reviewArchivedSubmissions', compact('session'));
    }

    public function allSubmissions($activityId)
    {
        $activity = Activity::where('activity_id', $activityId)
            ->with('section')
            ->firstOrFail();

        // Verify faculty owns this activity
        if ($activity->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $submissions = SimulationSession::where('activity_id', $activityId)
            ->with(['user'])
            ->where('status', '!=', 'in_progress')
            ->whereHas('user', function ($query) use ($activity) {
                $query->whereHas('sectionMembers', function ($q) use ($activity) {
                    $q->where('section_id', $activity->section_id)
                        ->where('is_archived', 1);
                });
            })
            ->orderBy('submitted_at', 'desc')
            ->get();

        $section = $activity->section;

        return view('faculty.allArchivedSubmissions', compact('activity', 'submissions', 'section'));
    }

    // ============================
    //     FOR STUDENT ARCHIVES
    // ============================


    public function archivedStudentSection()
    {
        $studentId = auth()->id(); // Get the logged-in student ID

        // Get section IDs where this student is a member
        $sectionIds = SectionMember::where('user_id', $studentId)
            ->pluck('section_id');

        // Get archived sections that the student belongs to
        $sections = Section::whereIn('section_id', $sectionIds)
            ->where('is_archived', true)
            ->get();

        // Compute section-level counts
        foreach ($sections as $section) {
            $section->totalStudents = SectionMember::where('section_id', $section->section_id)->count();
            $section->totalActivity = Activity::where('section_id', $section->section_id)->count();
        }

        // Total archived sections for this student
        $totalArchivedSections = $sections->count();

        // Get all students in the same archived sections (optional)
        $students = User::where('role', 'student')
            ->whereIn('id', function ($query) use ($sectionIds) {
                $query->select('user_id')
                    ->from('section_members')
                    ->whereIn('section_id', $sectionIds);
            })
            ->get();

        $studentCount = $students->count();

        return view('student.archivedSections', compact('sections', 'studentCount', 'students', 'totalArchivedSections'));
    }

    public function archivedStudentActivities($section_id)
    {
        $section = Section::where('section_id', $section_id)
            ->where('is_archived', true)
            ->firstOrFail();

        // Get activities
        $activities = Activity::where('section_id', $section_id)
            ->where('is_archived', true)
            ->with('user')
            ->get()
            ->map(function ($activity) {
                if ($activity->due_date) {
                    $dueDate = Carbon::parse($activity->due_date)->timezone('Asia/Manila');
                    $activity->formatted_due_date = $dueDate->format('F j, Y - g:i a');
                } else {
                    $activity->formatted_due_date = 'No due date';
                }
                $activity->type = 'activity'; // mark type
                return $activity;
            });

        // Get announcements
        $announcements = Announcement::where('section_id', $section_id)
            ->where('is_archived', true)
            ->with('user')
            ->get()
            ->map(function ($announcement) {
                $announcement->type = 'announcement'; // mark type
                return $announcement;
            });

        // Merge and sort by creation date descending
        $feed = $activities->merge($announcements)->sortByDesc('created_at');

        $totalStudents = SectionMember::where('section_id', $section_id)->count();
        $totalFeed = $feed->count();

        return view('student.archivedActivity', compact('section', 'feed', 'totalStudents', 'totalFeed'));
    }

    public function activityArchivedShow($section_id, $activity_id)
    {
        try {
            // Find the archived section
            $section = Section::where('section_id', $section_id)
                ->where('is_archived', true)
                ->firstOrFail();

            // Find the archived activity
            $activity = Activity::where('section_id', $section->section_id)
                ->where('is_archived', true)
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

            // Get simulation sessions for the current user and this activity
            $sessions = SimulationSession::where('user_id', auth()->id())
                ->where('activity_id', $activity->activity_id)
                ->get();

            // Categorize sessions
            $gradedSessions = $sessions->where('status', 'graded');
            $submittedSessions = $sessions->where('status', 'submitted');

            $completedSessions = SimulationSession::where('user_id', auth()->id())
                ->where('activity_id', $activity->activity_id)
                ->where('status', 'graded')
                ->pluck('role_name')
                ->toArray();

            $hasSubmitted = SimulationSession::where('user_id', auth()->id())
                ->where('activity_id', $activity->activity_id)
                ->whereIn('status', ['submitted', 'graded'])
                ->exists();

            // Log for debugging
            Log::info('Archived Activity Show Data', [
                'section_id' => $section->section_id,
                'activity_id' => $activity->activity_id,
                'activity_name' => $activity->name,
                'user_roles_count' => $userRoles->count(),
                'user_roles' => $userRoles->pluck('name')->toArray()
            ]);

            return view('student.archivedActivityDetails', compact(
                'section',
                'activity',
                'users',
                'userRoles',
                'completedSessions',
                'hasSubmitted',
                'sessions',
                'gradedSessions',
                'submittedSessions'
            ));
        } catch (\Exception $e) {
            Log::error('Error in archivedActivityShow', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'section_id' => $section_id,
                'activity_id' => $activity_id,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Error loading archived activity: ' . $e->getMessage());
        }
    }

    public function studentSection($section_id)
    {
        $section = Section::where('section_id', $section_id)->firstOrFail();
        $activity = Activity::first();

        $users = User::whereHas('sections', function ($query) use ($section) {
            $query->where('section_members.section_id', $section->section_id);
        })
            ->wherePivot('is_archived', true)
            ->where('role', 'student')
            ->get();

        return view('student.archivedStudent', compact('section', 'activity', 'users'));
    }
}
