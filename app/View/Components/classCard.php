<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class classCard extends Component
{
    public $role;
    public $background;
    public $profile;
    public $activity;
    public $users;

    public function __construct($activity = null, $users = null, $role = null, $background = null, $profile = null)
    {
        $this->activity = $activity;
        $this->users = $users;
        $this->role = $role;
        $this->background = $background;
        $this->profile = $profile;
    }

    public function render(): View|Closure|string
    {
        return view('components.class-card');
    }
}
