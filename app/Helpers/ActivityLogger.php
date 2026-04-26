<?php

namespace App\Helpers;

use App\Models\RecentActivity;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($action, $description = null, $sectionId = null, $userId = null)
    {
        RecentActivity::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'description' => $description,
            'section_id' => $sectionId,
        ]);
    }
}
