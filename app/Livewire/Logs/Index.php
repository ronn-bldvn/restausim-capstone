<?php

namespace App\Livewire\Logs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivityLog;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public function render()
    {
        abort_unless(auth()->user()->can('view activity logs'), 403);

        $user = auth()->user();
        $role = $user->getRoleNames()->first();

        $query = ActivityLog::query()->latest();

        //  Manager sees all
        if (!$user->hasRole('manager')) {
            //  everyone else sees only their own logs
            $query->where('user_id', $user->id)
                  ->where('role_name', $role);
        }

        return view('livewire.logs.index', [
            'logs' => $query->paginate(15),
        ]);
    }
}
