<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Section;
use App\Models\SimulationSession;
use App\Models\SimulationSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentSimulationController extends Controller
{
    public function mySubmissions(): View
    {
        $userId = Auth::id();
    
        $submissions = SimulationSubmission::with(['sessions.activity'])
            ->where('user_id', $userId)
            ->latest('submitted_at')
            ->get();
    
        $hasOpenSessions = SimulationSession::where('user_id', $userId)
            ->whereNull('submission_id')
            ->where('status', 'in_progress')
            ->exists();
    
        return view('student.my-submissions', compact('submissions', 'hasOpenSessions'));
    }
    protected function getOrCreateSession(Request $request, int $activityId, string $roleName): SimulationSession
    {
        $requestedSessionId = $request->query('session');

        if ($requestedSessionId) {
            $session = SimulationSession::where('id', $requestedSessionId)
                ->where('user_id', Auth::id())
                ->where('activity_id', $activityId)
                ->where('role_name', $roleName)
                ->firstOrFail();

            if (in_array($session->status, ['submitted', 'graded'])) {
                abort(403, 'This simulation session has already been submitted.');
            }

            session([
                'simulation_session_id' => $session->id,
                'simulation_role' => $roleName
            ]);

            return $session;
        }

        $session = SimulationSession::where('activity_id', $activityId)
            ->where('user_id', Auth::id())
            ->where('role_name', $roleName)
            ->whereNull('submission_id')
            ->where('status', 'in_progress')
            ->latest('created_at')
            ->first();

        if (!$session) {
            $session = SimulationSession::create([
                'activity_id' => $activityId,
                'user_id' => Auth::id(),
                'role_name' => $roleName,
                'started_at' => now(),
                'status' => 'in_progress',
                'session_data' => [],
            ]);
        }
        
        session([
            'simulation_session_id' => $session->id,
            'simulation_role' => $roleName
        ]);
        
        return $session;
            }

    public function showCashierSimulation(Request $request, $activityId)
    {
        $activity = Activity::findOrFail($activityId);
        $this->getOrCreateSession($request, $activityId, $activity->role->name);

        Auth::user()->syncRoles($activity->role->name);

        return to_route('floorplan.index');
    }

    public function showKitchenSimulation(Request $request, $activityId)
    {
        $activity = Activity::findOrFail($activityId);
        $this->getOrCreateSession($request, $activityId, $activity->role->name);

        Auth::user()->syncRoles($activity->role->name);

        return to_route('kitchen.dashboard');
    }

    public function showManagerSimulation(Request $request, $activityId)
    {
        $activity = Activity::findOrFail($activityId);
        $this->getOrCreateSession($request, $activityId, $activity->role->name);

        Auth::user()->syncRoles($activity->role->name);

        return to_route('inventory.index');
    }

    public function showWaiterSimulation(Request $request, $activityId)
    {
        $activity = Activity::findOrFail($activityId);
        $this->getOrCreateSession($request, $activityId, $activity->role->name);

        Auth::user()->syncRoles($activity->role->name);

        return to_route('floorplan.index');
    }

    public function showHostSimulation(Request $request, $activityId)
    {
        $activity = Activity::findOrFail($activityId);
        $this->getOrCreateSession($request, $activityId, $activity->role->name);

        Auth::user()->syncRoles($activity->role->name);

        return to_route('floorplan.index');
    }
}