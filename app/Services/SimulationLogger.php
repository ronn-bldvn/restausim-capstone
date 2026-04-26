<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SimulationLogger
{
    public static function log(
        string $action,
        ?string $roleName = null,
        ?Model $subject = null,
        array $properties = []
    ): void {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'simulation_session_id' => session('simulation_session_id'),
            'role_name' => $roleName ?? 'system',
            'action' => $action,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject ? $subject->getKey() : null,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}