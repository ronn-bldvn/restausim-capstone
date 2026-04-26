@php
    $role = auth()->user()->role;

    /* -------------------------
       ACTIVITIES LINK
    ------------------------- */
    if ($role === 'student') {
        $activitiesLink = isset($section) && $section->is_archived
            ? route('student/archived/activity/activity_details', [
                'section_id' => $section->section_id,
                'activity_id' => $activity->activity_id ?? 0
            ])
            : url('student/activity/' . $section->section_id);
    } else {
        $activitiesLink = isset($section) && $section->is_archived
            ? route('faculty/archived/activity/activity_details', [
                'section_id' => $section->section_id,
                'activity_id' => $activity->activity_id ?? 0
            ])
            : url('faculty/activity/' . $section->section_id);
    }

    /* -------------------------
        STUDENTS LINK
    ------------------------- */
    if ($role === 'student') {
        if (isset($section) && isset($activity) && $section->is_archived) {
            $studentsLink = route('student.archivedStudentS', [
                'section_id' => $section->section_id
            ]);
        } else {
            $studentsLink = url('student/student/' . $section->section_id);
        }
    } else {
        // For faculty
        if (isset($section) && $section->is_archived) {
            // Only create link if activity exists
            if (isset($activity) && $activity->activity_id) {
                $studentsLink = route('faculty.archivedStudentF', [
                    'sectionId' => $section->section_id,
                    'activityId' => $activity->activity_id
                ]);
            } else {
                // Fallback: disable the link or point to archived activities
                $studentsLink = route('faculty.archivedActivity', ['section_id' => $section->section_id]);
            }
        } else {
            $studentsLink = url(
                'faculty/student/' .
                $section->section_id . '/' .
                ($activity->activity_id ?? 0)
            );
        }
    }

@endphp

{{-- Top Navigation --}}
<div class="bg-[#f2f2f2] h-[48px] text-xs flex border-b border-black">

    {{-- Activities --}}
    <a href="{{ $activitiesLink }}"
       class="{{ request()->is('faculty/activity*')
            || request()->is('student/activity*')
            || request()->is('faculty/archived/activity*')
            || request()->is('student/archived/activity*')
                ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
        Activities
    </a>

    {{-- Students --}}
    {{-- @if ($role === 'faculty' && isset($section) && $section->is_archived && (!isset($activity) || !$activity->activity_id))
        Disabled state when no activity is available
        <span class="text-gray-400 font-medium px-4 py-4 cursor-not-allowed" title="Select an activity to view students">
            Students
        </span>
    @else
        <a href="{{ $studentsLink }}"
           class="{{ request()->is('faculty/student*')
            || request()->is('student/student*')
            || request()->is('faculty/archived/student/*')
            || request()->is('student/archived/student/*')
                    ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                    : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
            Students
        </a>
    @endif --}}


</div>
