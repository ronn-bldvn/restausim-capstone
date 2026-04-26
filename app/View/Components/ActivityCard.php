<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ActivityCard extends Component
{
    public $activity;
    public $users;

    /**
     * Create a new component instance.
     */
    public function __construct($activity, $users)
    {
        $this->activity = $activity;
        $this->users = $users;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.activity-card');
    }
}
