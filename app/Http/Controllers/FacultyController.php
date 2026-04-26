<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\EmailInvites;
use App\Models\Faculty;
use App\Models\RecentActivity;
use App\Models\Role;
use App\Models\Section;
use App\Models\SectionMember;
use App\Models\SimulationSession;
use App\Models\SimulationSubmission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacultyController extends Controller
{
    public function index()
    {
        $sections = Section::where('user_id', Auth::id())->get();
        $users = User::all();
        return view('faculty.section', compact('sections', 'users'));
    }

    public function facultyDashboard(Request $request, $section_id = null)
    {
        $facultyId = auth()->id();
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // SECTIONS (latest 4)
        $sections = Section::where('user_id', $facultyId)
            ->where('is_archived', false)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // SECTION COUNTS
        $totalSections = Section::where('user_id', $facultyId)
            ->where('is_archived', false)
            ->count();

        // GET SECTION IDS BELONGING TO THIS FACULTY
        $sectionIds = Section::where('user_id', $facultyId)
            ->pluck('section_id');

        // STUDENT COUNT
        $totalStudents = SectionMember::whereIn('section_id', $sectionIds)
            ->where('is_archived', false)
            ->count();

        // ACTIVITY COUNT
        $totalActivities = Activity::where('user_id', $facultyId)
            ->where('is_archived', false)
            ->count();

        // LATEST ACTIVITIES
        $latestActivities = Activity::where('user_id', $facultyId)
            ->where('is_archived', false)
            ->orderBy('created_at', 'desc')
            ->paginate(7);

            if ($request->ajax()) {
                return view('partials.ajax.activity', compact('latestActivities'))->render();
            }

        //GRADED ACTIVITIES
        $gradedCount = SimulationSubmission::where('status', 'graded')
            ->whereHas('sessions.activity', function ($q) use ($facultyId) {
                $q->where('user_id', $facultyId);
            })
            ->count();

        // RECENT ACTIVITIES (all)
        $recentActivities = RecentActivity::where('user_id', $facultyId)
            ->orWhereIn('section_id', $sectionIds)
            ->latest()
            ->take(5)
            ->get();

        $upcomingActivities = Activity::where('user_id', $facultyId)
        ->where('is_archived', false)
        ->whereBetween('due_date', [
            $today->startOfDay()->toDateTimeString(),
            $tomorrow->endOfDay()->toDateTimeString()
        ])
        ->orderBy('due_date', 'asc')
        ->take(5)
        ->get();

        // STUDENTS (for modal)
        $students = SectionMember::whereIn('section_id', $sectionIds)
            ->where('is_archived', false)
            ->with('user')
            ->get();

        // ALL ACTIVITIES (for modal)
        $activities = Activity::where('user_id', $facultyId)
            ->where('is_archived', false)
            ->orderBy('due_date', 'asc')
            ->get();

        // GRADED ACTIVITIES (for modal)
        $gradedActivities = SimulationSubmission::where('status', 'graded')
            ->whereHas('sessions.activity', function ($q) use ($facultyId) {
                $q->where('user_id', $facultyId);
            })
            ->with([
                'user',
                'sessions.activity'
            ])
            ->latest('submitted_at')
            ->get();

        return view('facultydashboard', compact(
            'totalSections',
            'totalStudents',
            'totalActivities',
            'sections',
            'latestActivities',
            'recentActivities',
            'gradedCount',
            'upcomingActivities',
            'students',
            'activities',       
            'gradedActivities',
        ));
    }

    public function showStudents($sectionId)
    {
        $section = Section::findOrFail($sectionId);
        // $activity = Activity::findOrFail($activityId);
        $roles = Role::all();

        // Get all students in this section
        $users = $section->students()
            ->with('sections')
            ->get();

        return view('faculty.student_section', compact('section',  'users', 'roles'));
    }
    
    public function showStudentsArchived($sectionId)
    {
        $section = Section::findOrFail($sectionId);
        $roles = Role::all();
    
        // Get archived students in this section
        $users = $section->students()
            ->with('sections')
            ->wherePivot('is_archived', 1)
            ->get();
    
        return view('faculty.students-archived', compact('section', 'users', 'roles'));
    }

    public function studentAllGrades()
    {
        $facultyId = auth()->id();

        // Sections owned by this faculty
        $sections = Section::where('user_id', $facultyId)
            ->where('is_archived', false)
            ->pluck('section_id');

        // Sessions ONLY from activities under faculty sections
        $sessions = SimulationSession::with([
            'activity.section',
            'user'
        ])
            ->whereHas('activity', function ($q) use ($sections) {
                $q->whereIn('section_id', $sections);
            })
            ->get();

        return view('faculty.allGrades', compact('sessions'));
    }

    public function showAllStudents()
    {
        $facultyId = auth()->id();

        $sections = Section::where('user_id', $facultyId)
            ->where('is_archived', false)
            ->get();

        $students = User::where('role', 'student')
            ->whereIn('id', function ($query) use ($facultyId) {
                $query->select('section_members.user_id')
                    ->from('section_members')
                    ->join('section', 'section.section_id', '=', 'section_members.section_id')
                    ->where('section.user_id', $facultyId)
                    ->where('section.is_archived', 0);
            })
            ->with(['sections' => function ($q) use ($facultyId) {
                $q->where('section.user_id', $facultyId)   // <- fully qualified
                    ->where('section.is_archived', 0);
            }])
            ->get();

        return view('faculty.students', compact('sections',  'students'));
    }
}
