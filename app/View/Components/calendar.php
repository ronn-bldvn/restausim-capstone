<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Calendar extends Component
{
    public $month;
    public $year;

    public function __construct($month = null, $year = null)
    {
        $this->month = $month ?? date("m");
        $this->year  = $year ?? date("Y");
    }

    public function render()
    {
        return view('components.calendar', [
            'calendarHtml' => $this->buildCalendar($this->month, $this->year)
        ]);
    }

private function buildCalendar($month, $year)
{
    $today = date('Y-m-d');
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $daysInMonth = date('t', $firstDayOfMonth);
    $monthName = date('F', $firstDayOfMonth);
    $dayOfWeek = date('w', $firstDayOfMonth);

    $prevMonth = $month - 1;
    $prevYear = $year;
    if ($prevMonth == 0) {
        $prevMonth = 12;
        $prevYear = $year - 1;
    }

    $nextMonth = $month + 1;
    $nextYear = $year;
    if ($nextMonth == 13) {
        $nextMonth = 1;
        $nextYear = $year + 1;
    }

    $calendar = "
    <div class='bg-white rounded-xl shadow-2xl p-6 w-full max-w-[400px]'>
        <!-- Header -->
        <div class='flex justify-between items-center mb-4'>
            <span class='text-xl font-bold font-[Barlow]'>Select Date</span>
            <button onclick='closeModal()' class='text-gray-500 hover:text-gray-700 text-2xl leading-none'>&times;</button>
        </div>

        <!-- Month/Year Navigation -->
        <div class='flex justify-between items-center mb-5 px-2'>
            <button onclick='loadCalendar($prevMonth, $prevYear)'
                    class='p-2 hover:bg-gray-100 rounded-lg transition-colors'>
                &lt;
            </button>
            <div class='text-lg font-semibold font-[Barlow]'>$monthName $year</div>
            <button onclick='loadCalendar($nextMonth, $nextYear)'
                    class='p-2 hover:bg-gray-100 rounded-lg transition-colors'>
                &gt;
            </button>
        </div>

        <!-- Weekdays -->
        <div class='grid grid-cols-7 text-center text-xs font-semibold text-gray-400 mb-2'>
            <div class='py-2'>S</div>
            <div class='py-2'>M</div>
            <div class='py-2'>T</div>
            <div class='py-2'>W</div>
            <div class='py-2'>T</div>
            <div class='py-2'>F</div>
            <div class='py-2'>S</div>
        </div>

        <!-- Days Grid -->
        <div class='grid grid-cols-7 gap-1'>
    ";

    $daysInPrevMonth = date('t', mktime(0, 0, 0, $month - 1, 1, $year));

    // Previous month days (grayed out)
    if ($dayOfWeek > 0) {
        for ($i = $dayOfWeek - 1; $i >= 0; $i--) {
            $dayNum = $daysInPrevMonth - $i;
            $prevMonthPadded = str_pad($prevMonth, 2, "0", STR_PAD_LEFT);
            $dayNumPadded = str_pad($dayNum, 2, "0", STR_PAD_LEFT);
            $date = "$prevYear-$prevMonthPadded-$dayNumPadded";

            $calendar .= "
            <div onclick='selectDate(\"$date\")'
                 class='font-[Inter] flex items-center justify-center rounded-full text-sm text-gray-300 w-10 h-10 cursor-pointer hover:bg-gray-100 transition-colors'>
                $dayNum
            </div>";
        }
    }

    // Current month days
    for ($currentDay = 1; $currentDay <= $daysInMonth; $currentDay++) {
        $monthPadded = str_pad($month, 2, "0", STR_PAD_LEFT);
        $dayPadded = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$monthPadded-$dayPadded";

        // Check if the date is in the past
        $isPast = $date < $today;

        $onclick = $isPast ? "" : "onclick='selectDate(\"$date\")'";
        $cursor = $isPast ? "cursor-not-allowed opacity-40" : "cursor-pointer hover:bg-blue-100";

        $calendar .= "
        <div $onclick
            class='font-[Inter] flex items-center justify-center rounded-full text-sm text-gray-800 w-10 h-10 $cursor transition-colors font-medium'>
            $currentDay
        </div>";

        $dayOfWeek++;
    }

    // Next month days (grayed out)
    $nextDay = 1;
    while ($dayOfWeek % 7 != 0) {
        $nextMonthPadded = str_pad($nextMonth, 2, "0", STR_PAD_LEFT);
        $nextDayPadded = str_pad($nextDay, 2, "0", STR_PAD_LEFT);
        $date = "$nextYear-$nextMonthPadded-$nextDayPadded";

        $calendar .= "
        <div onclick='selectDate(\"$date\")'
             class='font-[Inter] flex items-center justify-center rounded-full text-sm text-gray-300 w-10 h-10 cursor-pointer hover:bg-gray-100 transition-colors'>
            $nextDay
        </div>";

        $nextDay++;
        $dayOfWeek++;
    }

    $calendar .= "
        </div>
    </div>";

    return $calendar;
}
}
