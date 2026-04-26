<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Activity;
use App\Models\Section;
use App\Models\SectionMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{

    public function index()
    {
        $facultyId = auth()->id();

        // Get active sections
        $sections = Section::where('user_id', $facultyId)
            ->where('is_archived', false)
            ->latest()
            ->get();

        // Attach counts per section
        foreach ($sections as $section) {
            $section->totalStudents = SectionMember::where('section_id', $section->section_id)->count();
            $section->totalActivity = Activity::where('section_id', $section->section_id)->count();

            $activityIds = Activity::where('section_id', $section->section_id)
                ->pluck('activity_id');

            $section->totalGraded = DB::table('simulation_sessions')
                ->whereIn('activity_id', $activityIds)
                ->where('status', 'graded')
                ->count();
        }

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

        $studentCount = $students->count();

        return view('faculty.section', compact('sections', 'studentCount', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'section_name' => 'required|string|max:255',
            'class_code' => 'required|string|max:50',
            'class_name' => 'required|string|max:255',
        ]);

        // Generate unique share_code
        $shareCode = Section::generateShareCode();

        $section = Section::create([
            'user_id' => auth()->id(),
            'section_name' => $request->section_name,
            'class_code' => $request->class_code,
            'class_name' => $request->class_name,
            'share_code' => $shareCode,
            'invite_code' => Section::generateInviteCode(),
            'is_archived' => false,
        ]);

        ActivityLogger::log('Created new section', 'Section: ' . $section->section_name);

        return redirect()->route('faculty.activity', ['section_id' => $section->section_id])
            ->with('success', 'Section created successfully!');
    }

    public function update(Request $request, $section_id)
    {
        try {
            $section = Section::findOrFail($section_id);

            $section->update([
                'section_name' => $request->section_name,
                'class_name' => $request->class_name,
            ]);

            ActivityLogger::log('Updated the section', 'Section: ' . $section->section_name);

            return redirect()->back()->with('success', 'Section Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong:' . $e->getMessage());
        }
    }

    public function destroy($section_id)
    {
        try {
            $section = Section::findOrFail($section_id);
            $section->delete();
            ActivityLogger::log('Deleted the section', 'Section: ' . $section->section_name);
            return redirect()->back()->with('success', 'Section Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong:' . $e->getMessage());
        }
    }

    public function archive($section_id)
    {
        $section = Section::findOrFail($section_id);

        // Archive the section
        $section->update(['is_archived' => true]);

        // Archive activities
        Activity::where('section_id', $section_id)
            ->update(['is_archived' => true]);

        // Archive student memberships
        DB::table('section_members')
            ->where('section_id', $section_id)
            ->update(['is_archived' => true]);

        ActivityLogger::log('Archived the section', 'Section: ' . $section->section_name);

        return redirect()->back()->with('success', 'Section, activities, and student memberships archived successfully.');
    }

    public function restore($id)
    {
        $section = Section::findOrFail($id);
        $section->update(['is_archived' => false]);

        return redirect()->back()->with('success', 'Section restored successfully.');
    }


    // for modals (manage and viewDetails)
    public function getActivities($section_id)
    {
        try {
            $section = Section::findOrFail($section_id);

            if ($section->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $activities = Activity::with(['users' => function ($query) {
                $query->select('users.id', 'users.name', 'users.email');
            }])
                ->where('section_id', $section_id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $activities->load('users.roles');

            $totalStudents = SectionMember::where('section_id', $section_id)
                ->where('is_archived', false)
                ->count();

            $formattedActivities = $activities->map(function ($activity) use ($totalStudents) {
               

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

                    // Pass Data: Send the submitted count
                    'submitted_count' => $submittedCount,
                    'total_students' => $totalStudents,
                ];
            });

            return response()->json([
                'success' => true,
                'activities' => $formattedActivities
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading activities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load activities: ' . $e->getMessage()
            ], 500);
        }
    }

    // for modal (viewDetails)
    public function getStudents($section_id)
    {
        try {
            $section = Section::where('section_id', $section_id)->firstOrFail();

            if ($section->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $students = SectionMember::where('section_members.section_id', $section_id)
                ->where('section_members.is_archived', false)
                ->join('users', 'section_members.user_id', '=', 'users.id')
                ->leftJoin('activity_user_role', function ($join) use ($section_id) {
                    $join->on('users.id', '=', 'activity_user_role.user_id')
                        ->whereIn('activity_user_role.activity_id', function ($query) use ($section_id) {
                            $query->select('activity_id')
                                ->from('activities')
                                ->where('section_id', $section_id)
                                ->where('is_archived', false);
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
            \Log::error('Error loading students: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load students: ' . $e->getMessage()
            ], 500);
        }
    }

    // Copy invite link (returns JSON for AJAX)
    public function copyInviteLink($section_id)
    {
        $section = Section::findOrFail($section_id);

        if ($section->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (empty($section->share_code)) {
            $section->share_code = Section::generateUniqueCode();
            $section->save();
        }

        return response()->json([
            'success' => true,
            'link' => route('section.join', ['code' => $section->share_code]),
            'code' => $section->share_code
        ]);
    }

    // Show join page (for students)
    public function showJoinPage($code)
    {
        $section = Section::where('share_code', $code)->firstOrFail();
        $isMember = $section->members()->where('user_id', Auth::id())->exists();
        return view('student.join-section', compact('section', 'isMember'));
    }

    // Join section via code or link (for students)
    public function joinSection($code)
    {
        $section = Section::where('share_code', $code)->firstOrFail();

        if (Auth::user()->role !== 'student') {
            return redirect()->back()->with('error', 'Only students can join sections.');
        }

        if ($section->members()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('student.activity', ['section_id' => $section->section_id])
                ->with('info', 'You are already a member of this section.');
        }

        $section->members()->attach(Auth::id());
        $studentName = Auth::user()->name;

        ActivityLogger::log(
            'A student joined to this section',
            'Student: ' . $studentName . ' joined Section: ' . $section->section_name,
            $section->section_id
        );

        return redirect()->route('student.activity', ['section_id' => $section->section_id])
            ->with('success', 'Successfully joined ' . $section->section_name . '!');
    }

    // Regenerate invite code
    public function regenerateCode($section_id)
    {
        $section = Section::findOrFail($section_id);

        if ($section->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $section->share_code = Section::generateUniqueCode();
        $section->save();

        return response()->json([
            'success' => true,
            'link' => route('section.join', ['code' => $section->share_code]),
            'code' => $section->share_code
        ]);
    }
}
