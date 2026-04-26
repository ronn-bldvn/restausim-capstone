<?php

namespace App\Http\Controllers;

use App\Models\SimulationSession;
use App\Models\SimulationAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimulationController extends Controller
{
    /**
     * START SESSION
     */
public function startSession(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => ['required', 'integer'],
            'role_name' => ['required', 'string'],
        ]);

        $userId = Auth::id();

        $alreadySubmitted = SimulationSession::where('user_id', $userId)
            ->where('activity_id', $validated['activity_id'])
            ->where('role_name', $validated['role_name'])
            ->whereIn('status', ['submitted', 'graded'])
            ->exists();

        if ($alreadySubmitted) {
            return response()->json([
                'success' => false,
                'already_submitted' => true,
                'error' => 'You have already submitted this simulation.',
            ]);
        }

        $session = SimulationSession::where('user_id', $userId)
            ->where('activity_id', $validated['activity_id'])
            ->where('role_name', $validated['role_name'])
            ->whereNull('submission_id')
            ->where('status', 'in_progress')
            ->latest('id')
            ->first();

        if (!$session) {
            $session = SimulationSession::create([
                'activity_id' => $validated['activity_id'],
                'user_id' => $userId,
                'role_name' => $validated['role_name'],
                'started_at' => now(),
                'status' => 'in_progress',
                'session_data' => [],
            ]);
        }

        session(['simulation_session_id' => $session->id]);

        return response()->json([
            'success' => true,
            'session_id' => $session->id,
        ]);
    }



    /**
     * LOG ACTIONS
     */
    public function logAction(Request $request)
    {
        $validated = $request->validate([
            'action' => ['required', 'string'],
            'role_name' => ['nullable', 'string'],
            'subject_type' => ['nullable', 'string'],
            'subject_id' => ['nullable', 'integer'],
            'properties' => ['nullable', 'array'],
        ]);

        $sessionId = session('simulation_session_id');

        ActivityLog::create([
            'user_id' => Auth::id(),
            'simulation_session_id' => $sessionId,
            'role_name' => $validated['role_name'] ?? null,
            'action' => $validated['action'],
            'subject_type' => $validated['subject_type'] ?? null,
            'subject_id' => $validated['subject_id'] ?? null,
            'properties' => $validated['properties'] ?? [],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'session_id' => $sessionId,
        ]);
    }


    /**
     * SUBMIT SESSION
     */
    public function submitSession(Request $request, $sessionId)
{
    $session = SimulationSession::findOrFail($sessionId);

    // Prevent unauthorized submit
    if ($session->user_id !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Prevent double submission
    if (in_array($session->status, ['submitted', 'graded'])) {
        return response()->json([
            'success' => false,
            'message' => 'This simulation was already submitted.'
        ], 400);
    }

    // Calculate session metrics
    $actions = $session->actions;
    $duration = (time() - strtotime($session->started_at)) / 60;

    $actionsByType = $actions->groupBy('action_type')->map->count();

    $metrics = array_merge([
        'total_actions' => $actions->count(),
        'duration_minutes' => round($duration, 2),
        'actions_by_type' => $actionsByType,
    ], $request->metrics ?? []);

    // Finalize session
    $session->update([
        'status'       => 'submitted',  // ← Keep as 'submitted'
        'submitted_at' => now(),
        'session_data' => $metrics
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Simulation submitted successfully'
    ]);
}


    /**
     * GET SESSION DETAILS
     */
    public function getSession($sessionId)
    {
        $session = SimulationSession::with('actions')->findOrFail($sessionId);

        if ($session->user_id !== Auth::id() && Auth::user()->role !== 'faculty') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($session);
    }


    /**
     * CHECK IF USER ALREADY SUBMITTED
     */
    public function checkSubmission(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => ['required', 'integer'],
            'role_name' => ['required', 'string'],
        ]);

        $submitted = SimulationSession::where('user_id', Auth::id())
            ->where('activity_id', $validated['activity_id'])
            ->where('role_name', $validated['role_name'])
            ->whereIn('status', ['submitted', 'graded'])
            ->exists();

        return response()->json([
            'submitted' => $submitted,
        ]);
    }
}
