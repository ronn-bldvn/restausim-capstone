<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\SimulationAction;
use App\Models\SimulationSession;
use App\Models\SimulationSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SimulationSubmissionController extends Controller
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

    public function submitAllOpenSessions(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        $openSessions = SimulationSession::with('activity')
            ->where('user_id', $userId)
            ->whereNull('submission_id')
            ->where('status', 'in_progress')
            ->orderBy('started_at')
            ->get();

        if ($openSessions->isEmpty()) {
            return back()->with('error', 'No open simulation sessions found.');
        }

        DB::transaction(function () use ($openSessions, $userId): void {
            $simulationName = $this->generateStudentFriendlySimulationName($openSessions);

            $submission = SimulationSubmission::create([
                'user_id' => $userId,
                'batch_code' => 'SUB-' . now()->format('YmdHis') . '-' . $userId,
                'simulation_name' => $simulationName,
                'submitted_at' => now(),
                'status' => 'submitted',
                'summary' => [],
            ]);

            $summary = $this->processSessionsIntoSubmission($openSessions, $submission, $userId);

            $submission->update([
                'summary' => $this->buildSubmissionSummary($openSessions, $summary),
            ]);
        });

        session()->forget('simulation_session_id');

        return redirect()
            ->route('student.my-submissions')
            ->with('success', 'Simulation submitted successfully.');
    }

    public function facultyIndex($sectionId): View
    {
        $section = \App\Models\Section::findOrFail($sectionId);

        $submissions = SimulationSubmission::with(['user', 'sessions.activity', 'actions'])
            ->whereHas('sessions.activity', function ($query) use ($sectionId) {
                $query->where('section_id', $sectionId);
            })
            ->latest('submitted_at')
            ->get();

        return view('faculty.simulation.submissions', compact('section', 'submissions'));
    }
    
    public function facultyIndexArchive($sectionId): View
    {
        $section = \App\Models\Section::findOrFail($sectionId);

        $submissions = SimulationSubmission::with(['user', 'sessions.activity', 'actions'])
            ->whereHas('sessions.activity', function ($query) use ($sectionId) {
                $query->where('section_id', $sectionId);
            })
            ->latest('submitted_at')
            ->get();

        return view('faculty.simulation.archivedsubmissions', compact('section', 'submissions'));
    }

    public function review(Request $request, $submissionId)
    {
        $submission = SimulationSubmission::with([
            'user',
            'sessions.activity',
        ])->findOrFail($submissionId);

        $actions = SimulationAction::where('submission_id', $submission->id)
            ->orderBy('timestamp')
            ->orderBy('id')
            ->paginate(5)
            ->withQueryString();

        if ($request->ajax()) {
            return view('partials.ajax.actions-table', compact('actions'))->render();
        }

        return view('faculty.simulation.review', compact('submission', 'actions'));
    }

    public function grade(Request $request, $submissionId): RedirectResponse
    {
        $validated = $request->validate([
            'score' => ['required', 'numeric', 'min:0'],
            'feedback' => ['nullable', 'string'],
        ]);
    
        $submission = SimulationSubmission::findOrFail($submissionId);
    
        $submission->update([
            'score' => $validated['score'],
            'feedback' => $validated['feedback'] ?? null,
            'status' => 'graded',
        ]);
    
        SimulationSession::where('submission_id', $submission->id)->update([
            'status' => 'graded',
            'feedback' => $validated['feedback'] ?? null,
        ]);
    
        return back()->with('success', 'Submission graded successfully.');
    }

    public function rebuildSubmission($submissionId): RedirectResponse
    {
        $submission = SimulationSubmission::with('sessions.activity')->findOrFail($submissionId);

        if ($submission->sessions->isEmpty()) {
            return back()->with('error', 'This submission has no linked sessions.');
        }

        DB::transaction(function () use ($submission): void {
            SimulationAction::where('submission_id', $submission->id)->delete();

            $sessions = SimulationSession::with('activity')
                ->where('submission_id', $submission->id)
                ->orderBy('started_at')
                ->get();

            $summary = $this->processSessionsIntoSubmission(
                $sessions,
                $submission,
                $submission->user_id,
                true
            );

            $submission->update([
                'simulation_name' => $this->generateStudentFriendlySimulationName($sessions),
                'summary' => $this->buildSubmissionSummary($sessions, $summary),
            ]);
        });

        return back()->with('success', "Submission #{$submissionId} rebuilt successfully.");
    }

    private function generateStudentFriendlySimulationName(Collection $sessions): string
    {
        $activityNames = $sessions
            ->pluck('activity.name')
            ->filter()
            ->unique()
            ->values();

        $roles = $sessions
            ->pluck('role_name')
            ->filter()
            ->unique()
            ->values();

        if ($activityNames->count() === 1) {
            $baseName = $activityNames->first();
        } elseif ($activityNames->count() === 2) {
            $baseName = $activityNames->implode(' + ');
        } else {
            $baseName = 'Restaurant Simulation - ' . $activityNames->count() . ' Activities';
        }

        if ($roles->isNotEmpty()) {
            $baseName .= ' (' . $roles->implode(', ') . ')';
        }

        return $baseName;
    }

    private function processSessionsIntoSubmission(
        Collection $sessions,
        SimulationSubmission $submission,
        int $userId,
        bool $isRebuild = false
    ): array {
        $totalOrders = 0;
        $totalRevenue = 0;
        $totalActions = 0;
        $processedLogIds = [];

        foreach ($sessions as $session) {
            $logs = ActivityLog::where('simulation_session_id', $session->id)
                ->where('user_id', $userId)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get();

            $sessionOrders = 0;
            $sessionRevenue = 0;
            $sessionActionCount = 0;

            foreach ($logs as $log) {
                if (in_array($log->id, $processedLogIds, true)) {
                    continue;
                }

                $processedLogIds[] = $log->id;

                $metrics = $this->extractLogMetrics($log);

                $sessionOrders += $metrics['orders'];
                $sessionRevenue += $metrics['revenue'];
                $sessionActionCount++;

                $totalOrders += $metrics['orders'];
                $totalRevenue += $metrics['revenue'];
                $totalActions++;

                $this->storeAction($submission, $session, $log);
            }

            $session->update([
                'submission_id' => $submission->id,
                'submitted_at' => $session->submitted_at ?? $submission->submitted_at ?? now(),
                'status' => $submission->status === 'graded' ? 'graded' : 'submitted',
                'session_data' => [
                    'logs_count' => $logs->count(),
                    'session_orders' => $sessionOrders,
                    'session_revenue' => $sessionRevenue,
                    'session_actions' => $sessionActionCount,
                    'submission_id' => $submission->id,
                    'rebuilt' => $isRebuild,
                ],
            ]);
        }

        return [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'total_actions' => $totalActions,
        ];
    }

    private function storeAction(
        SimulationSubmission $submission,
        SimulationSession $session,
        ActivityLog $log
    ): void {
        $alreadyExists = SimulationAction::where('submission_id', $submission->id)
            ->where('action_data->source_log_id', $log->id)
            ->exists();

        if ($alreadyExists) {
            return;
        }

        SimulationAction::create([
            'session_id' => $session->id,
            'submission_id' => $submission->id,
            'action_type' => $log->action,
            'action_data' => [
                'source_log_id' => $log->id,
                'role_name' => $log->role_name,
                'subject_type' => $log->subject_type,
                'subject_id' => $log->subject_id,
                'properties' => $log->properties,
                'logged_at' => optional($log->created_at)->toDateTimeString(),
            ],
            'timestamp' => $log->created_at,
        ]);
    }

    private function extractLogMetrics(ActivityLog $log): array
    {
        $properties = is_array($log->properties) ? $log->properties : [];

        $orders = match ($log->action) {
            'order_saved' => 1,
            default => 0,
        };

        $revenue = match ($log->action) {
            'payment_completed' => (float) (
                $properties['amount_paid']
                ?? $properties['amount']
                ?? $properties['total']
                ?? 0
            ),
            default => 0,
        };

        return [
            'orders' => $orders,
            'revenue' => $revenue,
        ];
    }

    private function buildSubmissionSummary(Collection $sessions, array $summary): array
    {
        $sessionIds = $sessions->pluck('id')->values()->all();
        $activityIds = $sessions->pluck('activity_id')->unique()->values()->all();
        $roles = $sessions->pluck('role_name')->unique()->values()->all();

        $durationMinutes = $sessions->sum(function ($session) {
            if (!$session->started_at) {
                return 0;
            }

            $endTime = $session->submitted_at ?? now();

            return $session->started_at->diffInMinutes($endTime);
        });

        return [
            'session_ids' => $sessionIds,
            'activity_ids' => $activityIds,
            'roles' => $roles,
            'total_sessions' => count($sessionIds),
            'total_orders' => $summary['total_orders'] ?? 0,
            'total_actions' => $summary['total_actions'] ?? 0,
            'total_revenue' => $summary['total_revenue'] ?? 0,
            'duration_minutes' => $durationMinutes,
        ];
    }
}