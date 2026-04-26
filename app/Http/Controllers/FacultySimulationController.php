<?php

// app/Http/Controllers/FacultySimulationController.php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\SimulationSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\ActivityGradedMail;
use Illuminate\Support\Facades\Mail;

class FacultySimulationController extends Controller
{
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

        return view('faculty.review-simulation', compact('session'));
    }

    public function gradeSession(Request $request, $sessionId)
    {
        $session = SimulationSession::with('activity')->findOrFail($sessionId);

        // Verify faculty owns this activity
        if ($session->activity->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'score' => 'required|numeric|min:0|max:' . $session->activity->grades,
            'feedback' => 'nullable|string'
        ]);

        $session->update([
            'status' => 'graded',
            'score' => $request->score,
            'feedback' => $request->feedback
        ]);

        // send graded email
        if (!empty($session->user->email)) {
            Mail::to($session->user->email)
                ->send(new ActivityGradedMail($session));
        }


        return response()->json([
            'success' => true,
            'message' => 'Graded successfully'
        ]);
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
            ->orderBy('submitted_at', 'desc')
            ->get();

        $section = $activity->section;

        return view('faculty.all-submissions', compact('activity', 'submissions', 'section'));
    }
}
