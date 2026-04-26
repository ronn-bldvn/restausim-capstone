<x-layouts title="Students | {{ $section->class_name }}">
<div class="flex-1 overflow-y-auto">
    <div class="rounded-xl h-full shadow-sm">
        <div class="bg-[#f2f2f2] h-[48px] text-xs flex border-b border-black">
            @include('partials.includes.topnav', ['activity' => $activity ?? null])
        </div>

        <div class="overflow-auto">
            <x-table id="" :headers="['Name', 'Email', 'Username', 'Section', 'Role']" align="text-center">
                @php $count = 1; @endphp
                @foreach ($users as $user)
                    @if ($user->role === 'student')
                        <tr class="text-center">
                            <td class="px-4 py-2 flex items-center gap-2">
                                <span>{{ $count++ . '.' }}</span>
                                <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                                <span>{{ $user->name }}</span>
                            </td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">{{ $user->username }}</td>
                            <td class="px-4 py-2">{{ $section->section_name }}</td>
                            <td class="p-3">
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
                                                <option disabled value="{{ $role->id }}" {{ $currentRole && $currentRole->id == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <span class="text-gray-500 text-sm">Select an activity to assign roles</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </x-table>
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

