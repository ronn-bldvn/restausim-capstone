<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\View\Components\Calendar;
use App\View\Components\Time;

class DateTimePicker extends Component
{
    public $month;
    public $year;
    public $hour;
    public $minute;
    public $ampm;

    public function __construct($month = null, $year = null, $hour = '11', $minute = '59', $ampm = 'PM')
    {
        $this->month = $month ?? date("m");
        $this->year  = $year ?? date("Y");
        $this->hour  = $hour;
        $this->minute = $minute;
        $this->ampm   = $ampm;
    }

    public function render()
    {
        return view('components.date-time-picker');
    }
}
