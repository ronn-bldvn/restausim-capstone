<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Activity;
use App\Models\Role;
use App\Models\Section;
use App\Models\SectionMember;
use App\Models\User;
use App\Mail\NewActivityMail;
use App\Models\SimulationSession;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function index($section_id)
    {
        // Fetch the section, fail if it doesn't exist
        $section = Section::findOrFail($section_id);


        return view('faculty.studentActivity', compact('section'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'        => 'required|string',
                'description' => 'required|string',
                'grades'      => 'nullable|string',
                'due_date'    => 'nullable|date',
                'section_id'  => 'required|integer|exists:section,section_id',
                'role' => 'required|exists:activity_roles,id'
            ]);

            $dueDate = null;

            // Only process due_date if it's provided and not empty
            if ($request->has('due_date') && !empty($request->due_date)) {
                try {
                    $dueDate = Carbon::parse($request->due_date, 'Asia/Manila');

                    // Warning condition: due date in the past
                    if ($dueDate->isPast()) {
                        return redirect()->back()
                            ->withInput()
                            ->with('warning', 'The due date you entered is already in the past.');
                    }
                } catch (Exception $e) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Invalid due date format.');
                }
            }

            //  Create with explicit due_date value (null if not set)
            $activity = Activity::create([
                'name'        => $request->name,
                'description' => $request->description,
                'grades'      => $request->grades,
                'role_id'     => $request->role,
                'due_date'    => $dueDate, // This will be null if no date selected
                'section_id'  => $request->section_id,
                'user_id'     => Auth::id(),
            ]);

            $faculty = auth()->user();

            $section = $activity->section; // Assuming Activity has a 'section' relationship

            $members = SectionMember::where('section_id', $activity->section_id)
                ->with('user')
                ->get();

            foreach ($members as $member) {
                $student = $member->user;

                if ($student && filter_var($student->email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        Mail::to($student->email)->send(new NewActivityMail($activity, $section, $student, $faculty));
                    } catch (Exception $e) {
                        \Log::error("Failed sending new activity email to {$student->email}: " . $e->getMessage());
                    }
                } else {
                    \Log::warning("Skipped sending new activity email: invalid or missing email for SectionMember ID {$member->id}");
                }
            }

            ActivityLogger::log('Created new activity', 'Activity: ' . $activity->name);

            return redirect()->back()->with('success', 'Activity created successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while creating the activity: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $section_id, $activity_id)
    {
        $activity = Activity::find($activity_id);

        if (!$activity) {
            return redirect()->back()->with('error', 'Activity not found.');
        }

        $request->validate([
            'activity_name' => 'required|string',
            'activity_description' => 'nullable|string',
            'activity_grade' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $updateData = [
            'name'        => $request->activity_name,
            'description' => $request->activity_description,
            'grades'      => $request->activity_grade,
        ];

        if ($request->filled('due_date')) {
            try {
                $newDueDate = Carbon::parse($request->due_date, 'Asia/Manila');

                $newFormatted = $newDueDate->format('Y-m-d H:i');
                $oldFormatted = $activity->due_date
                    ? Carbon::parse($activity->due_date, 'Asia/Manila')->format('Y-m-d H:i')
                    : null;

                if ($newFormatted !== $oldFormatted) {
                    if ($newDueDate->isPast()) {
                        return redirect()->back()->withInput()->with('warning', 'Due date cannot be in the past.');
                    }
                    $updateData['due_date'] = $newDueDate;
                }
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Invalid due date format: ' . $e->getMessage());
            }
        }

        ActivityLogger::log('Updated the activity', 'Activity: ' . $activity->name);

        $activity->update($updateData);

        return redirect()->back()->with('success', 'Activity updated successfully.');
    }

    public function destroy($section_id, $activity_id)
    {
        try {
            $activity = Activity::where('activity_id', $activity_id)
                ->where('section_id', $section_id)
                ->first();

            if (!$activity) {
                return redirect()->back()->with('error', "Activity not found. Activity ID: $activity_id, Section ID: $section_id");
            }

            $activity->delete();

            ActivityLogger::log('Deleted the activity', 'Activity: ' . $activity->name);

            return redirect()->back()->with('success', 'Activity Deleted Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function show($section_id)
    {
        $section = Section::findOrFail($section_id);

        $activities = Activity::where('section_id', $section_id)
            ->withCount('simulationSessions')
            ->with('user')
            ->latest()
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

        return view('faculty.activity', compact('section', 'activities', 'totalActivity', 'totalStudents'));
    }


    public function activityShow($section_id, $activity_id)
    {
        $facultyId = auth()->id();
    
        $section = Section::where('section_id', $section_id)
            ->where('user_id', $facultyId)
            ->firstOrFail();
    
        $activity = Activity::where('section_id', $section->section_id)
            ->where('activity_id', $activity_id)
            ->firstOrFail();
    
        $activityRole = DB::table('activity_roles')
            ->where('id', $activity->role_id)
            ->value('name');
    
        $users = User::where('users.section_id', $section->section_id)
            ->where('users.role', 'student')
            ->leftJoin('activity_user_role', function ($join) use ($activity_id) {
                $join->on('users.id', '=', 'activity_user_role.user_id')
                    ->where('activity_user_role.activity_id', '=', $activity_id);
            })
            ->leftJoin('activity_roles', 'activity_user_role.role_id', '=', 'activity_roles.id')
            ->select(
                'users.*',
                'activity_roles.name as simulation_role'
            )
            ->get();
    
        $submissions = SimulationSession::where('simulation_sessions.activity_id', $activity_id)
            ->whereIn('simulation_sessions.status', ['submitted', 'graded'])
            ->leftJoin('activity_user_role', function ($join) use ($activity_id) {
                $join->on('simulation_sessions.user_id', '=', 'activity_user_role.user_id')
                    ->where('activity_user_role.activity_id', '=', $activity_id);
            })
            ->leftJoin('activity_roles', 'activity_user_role.role_id', '=', 'activity_roles.id')
            ->select(
                'simulation_sessions.*',
                'activity_roles.name as simulation_role'
            )
            ->with('user')
            ->orderBy('simulation_sessions.created_at', 'desc')
            ->take(10)
            ->get();
    
        return view('faculty.activity_details', compact(
            'section',
            'activity',
            'users',
            'submissions',
            'activityRole'
        ));
    }

    public function getActivitySubmissions($activity_id)
    {
        try {
            $activity = Activity::findOrFail($activity_id);
            $sectionId = $activity->section_id;

            // Get all students in the section
            $allStudents = SectionMember::where('section_id', $sectionId)
                ->where('is_archived', false)
                ->with('user')
                ->get()
                ->pluck('user'); // Get the user objects

            // Get all sessions for this activity
            $sessions = DB::table('simulation_sessions')
                ->where('activity_id', $activity_id)
                ->get()
                ->keyBy('user_id'); // Key by user_id for easy lookup

            // Categorize Students
            $assigned = []; // No submission or in_progress
            $turnedin = []; // Submitted but not graded
            $graded = [];   // Graded

            foreach ($allStudents as $student) {
                $session = $sessions->get($student->id);

                $studentData = [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'status' => $session ? $session->status : 'assigned',
                    'score' => $session ? $session->score : null,
                    'submitted_at' => $session && $session->submitted_at ? date('M d, g:i A', strtotime($session->submitted_at)) : null,
                ];

                if (!$session || $session->status == 'in_progress') {
                    $assigned[] = $studentData;
                } elseif ($session->status == 'submitted') {
                    $turnedin[] = $studentData;
                } elseif ($session->status == 'graded') {
                    $graded[] = $studentData;
                }
            }

            return response()->json([
                'success' => true,
                'counts' => [
                    'total_students' => $allStudents->count(),
                    'submitted' => count($turnedin) + count($graded), // Total who finished
                    'graded' => count($graded),
                    'pending' => count($turnedin)
                ],
                'lists' => [
                    'assigned' => $assigned,
                    'turnedin' => $turnedin,
                    'graded' => $graded
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
