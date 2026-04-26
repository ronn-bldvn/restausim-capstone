@php

$role = auth()->user()->role;

$studentsLink = url(
    'faculty/student/' .
    $section->section_id
);

@endphp

<x-layouts title="Students | {{ $section->class_name }}">
    <div class="flex-1 overflow-y-auto">
        <div class="bg-[#f2f2f2] h-[48px] text-xs flex border-b border-black">
                <!-- Activities -->
                <a href="{{ auth()->user()->role === 'student'
                        ? url('student/activity/' . $section->section_id)
                        : url('faculty/activity/' . $section->section_id) }}"
                        class="{{ request()->is('faculty/activity*') || request()->is('student/activity*')
                            ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                            : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                        Activities
                </a>

                <!-- Announcements -->
                <a href="{{ url('faculty/announcements/' . $section->section_id) }}"
                    class="{{ request()->is('faculty/announcements*')
                        ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                        : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                    Announcements
                </a>

                @if ($role === 'faculty' && isset($section) && $section->is_archived && (!isset($activity) || !$activity->activity_id))
                    {{-- Disabled state when no activity is available --}}
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
                @endif
                
                <a href="{{ route('faculty.simulation.all', $section->section_id) }}"
                   class="{{ request()->is('faculty/simulation/all-submissions*')
                        ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                        : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                    Student Works
                </a>

            </div>
            <div class="overflow-auto">
                <!-- Desktop view: Full table (hidden on sm/md) -->
                <div class="hidden md:block">
                    <x-table id="" :headers="['Name']">
                        @php $count = 1; @endphp
                        @foreach ($users as $user)
                            @if ($user->role === 'student')
                                <tr class="text-center">
                                    <td class="px-4 py-2 flex items-center gap-2">
                                        <span>{{ $count++ . '.' }}</span>
                                        <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                                        <span>{{ $user->name }}</span>
                                    </td>
                                    {{-- <td class="px-4 py-2">{{ $user->email }}</td>
                                    <td class="px-4 py-2">{{ $section->section_name }}</td> --}}
                                    {{-- <td class="p-3">
                                        <div class="flex justify-center items-center">
                                            @if(isset($activity))
                                                @php
                                                    $currentRole = $user->roles()
                                                        ->wherePivot('activity_id', $activity->activity_id)
                                                        ->first();
                                                @endphp
                                                <select class="border border-black p-2 rounded-lg activity-role"
                                                    data-user="{{ $user->id }}"
                                                    data-activity="{{ $activity->activity_id }}">
                                                    <option value="">No Role</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}" {{ $currentRole && $currentRole->id == $role->id ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <span class="text-gray-500 text-sm">Select an activity to assign roles</span>
                                            @endif
                                        </div>
                                    </td> --}}
                                </tr>
                            @endif
                        @endforeach
                    </x-table>
                </div>

                <!-- Mobile/Tablet view: Only dropdowns (visible on sm/md) -->
                <div class="md:hidden p-4 space-y-3">
                    @php $count = 1; @endphp
                    @foreach ($users as $user)
                        @if ($user->role === 'student')
                            <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="text-gray-600 font-semibold">{{ $count++ . '.' }}</span>
                                    <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                                    <span class="font-medium text-gray-900">{{ $user->name }}</span>
                                </div>

                                {{-- @if(isset($activity))
                                    @php
                                        $currentRole = $user->roles()
                                            ->wherePivot('activity_id', $activity->activity_id)
                                            ->first();
                                    @endphp
                                    <select class="w-full border border-black p-2 rounded-lg activity-role"
                                        data-user="{{ $user->id }}"
                                        data-activity="{{ $activity->activity_id }}">
                                        <option value="">No Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ $currentRole && $currentRole->id == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <span class="text-gray-500 text-sm">Select an activity to assign roles</span>
                                @endif --}}
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

{{-- ✅ Alert container --}}
<div id="alert-container" class="fixed top-6 right-6 space-y-2 z-[9999]"></div>
</x-layouts>

{{-- ✅ Script section --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.activity-role').on('change', function() {
        const userId = $(this).data('user');
        const activityId = $(this).data('activity');
        const roleId = $(this).val();

        $.ajax({
            url: "{{ route('faculty.activity.assign-role') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                user_id: userId,
                activity_id: activityId,
                role_id: roleId
            },
            success: function(response) {
                showAlert('success', response.message || 'Role assigned successfully!');
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Something went wrong.';
                showAlert('error1', errorMsg);
            }
        });
    });

    // Toast Alert Function (styled like your Blade 'success' toast)
    function showAlert(type, message) {
        const alertId = 'toast-' + Date.now();

        const config = {
            success: {
                bg: 'bg-white border border-gray-100',
                iconBg: 'bg-green-100',
                iconColor: 'text-green-500',
                text: 'text-gray-900',
                title: 'Success!'
            },
            error1: {
                bg: 'bg-white border border-gray-100',
                iconBg: 'bg-red-100',
                iconColor: 'text-red-600',
                text: 'text-gray-900',
                title: 'Error'
            },
            warning: {
                bg: 'bg-white border border-gray-100',
                iconBg: 'bg-yellow-100',
                iconColor: 'text-yellow-600',
                text: 'text-gray-900',
                title: 'Warning'
            }
        };

        const c = config[type] || config.success;

        const alertHTML = `
            <div id="${alertId}"
                 class="fixed top-6 right-6 flex items-center w-80 p-4 rounded-xl shadow-lg ${c.bg} transform translate-x-full opacity-0 transition-all duration-500 ease-out z-50">

                <!-- Left Icon Circle -->
                <div class="flex-shrink-0 rounded-full ${c.iconBg} p-2">
                    ${
                        type === 'success'
                            ? `<svg class="w-5 h-5 ${c.iconColor}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                               </svg>`
                            : type === 'error1'
                            ? `<svg class="w-5 h-5 ${c.iconColor}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                               </svg>`
                            : `<svg class="w-5 h-5 ${c.iconColor}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m0 0l-3-3m3 3l3-3M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                               </svg>`
                    }
                </div>

                <!-- Message -->
                <div class="ml-3 flex-1">
                    <p class="font-semibold ${c.text}">${c.title}</p>
                    <p class="text-sm text-gray-600">${message}</p>
                </div>

                <!-- Close Button -->
                <button type="button" class="ml-3 text-gray-400 hover:text-gray-600 focus:outline-none" onclick="$('#${alertId}').remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        `;

        $('body').append(alertHTML);
        const alert = document.querySelector(`#${alertId}`);

        // Slide-in and slide-out animation
        setTimeout(() => {
            alert.classList.remove('translate-x-full', 'opacity-0');
            alert.classList.add('translate-x-0', 'opacity-100');
        }, 100);

        setTimeout(() => {
            alert.classList.remove('translate-x-0', 'opacity-100');
            alert.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    }
});
</script>
