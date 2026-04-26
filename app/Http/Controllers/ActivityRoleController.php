<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Activity;
use App\Helpers\ActivityLogger;

class ActivityRoleController extends Controller
{
    public function assignActivityRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'activity_id' => 'required|exists:activities,activity_id',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $user = User::find($request->user_id);
        $activity = Activity::find($request->activity_id);
        $roleId = $request->role_id;

        // Find existing pivot record (if any)
        $existingRole = $user->activities()
            ->wherePivot('activity_id', $activity->activity_id)
            ->first()?->pivot?->role_id;

        // Detach old pivot entry
        $user->activities()->detach($activity->activity_id);

        // Attach new role if provided
        if ($roleId) {
            $user->activities()->attach($activity->activity_id, ['role_id' => $roleId]);
        }

        // Get readable role names for logs
        $oldRoleName = $existingRole ? Role::find($existingRole)?->name : null;
        $newRoleName = $roleId ? Role::find($roleId)?->name : null;

        // Log the action
        if (!$existingRole && $roleId) {
            ActivityLogger::log(
                'Assigned Role',
                "Assigned role '{$newRoleName}' to student {$user->name} for activity '{$activity->name}'"
            );
        } elseif ($existingRole && $roleId && $existingRole != $roleId) {
            ActivityLogger::log(
                'Changed Role',
                "Changed role for student {$user->name} in activity '{$activity->name}' from '{$oldRoleName}' to '{$newRoleName}'."
            );
        } elseif ($existingRole && !$roleId) {
            ActivityLogger::log(
                'Removed Role',
                "Removed role '{$oldRoleName}' from student {$user->name} in activity '{$activity->name}'."
            );
        }

        return response()->json(['message' => 'Role updated successfully.']);
    }
}
