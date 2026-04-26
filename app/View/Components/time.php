<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Time extends Component
{
    public $hour;
    public $minute;
    public $ampm;

    public function __construct($hour = '11', $minute = '59', $ampm = 'PM')
    {
        $this->hour = str_pad($hour, 2, "0", STR_PAD_LEFT);
        $this->minute = str_pad($minute, 2, "0", STR_PAD_LEFT);
        $this->ampm = strtoupper($ampm) === 'AM' ? 'AM' : 'PM';
    }

    public function render()
    {
        return view('components.time');
    }
}
