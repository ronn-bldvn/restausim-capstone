<?php

namespace App\View\Components;

use Illuminate\View\Component;

class section extends Component
{
    public $section;
    public $activity;
    public $variant;

    public function __construct($section, $variant = 'exit', $activity = null)
    {
        $this->section = $section;
        $this->variant = $variant;
        $this->activity = $activity;
    }

    public function render()
    {
        return view('components.section');
    }
}
