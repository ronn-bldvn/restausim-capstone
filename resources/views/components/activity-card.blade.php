@props(['activity', 'users', 'section', 'submissions', 'activityRole'])

<div class="bg-white flex flex-col rounded-xl px-4 sm:px-6 lg:px-8 py-4 sm:py-6 gap-4 m-0 md:m-5 overflow-hidden">

    <div class="flex flex-col w-full min-w-0">
        <div class="flex items-center justify-between w-full gap-4">
            <h2 class="font-[Poppins] text-2xl sm:text-3xl lg:text-4xl font-semibold break-words">
                {{ $activity->name }}
            </h2>
        
            <x-button variant="cancel" class="shrink-0">
                @if (Auth::user()->role === 'student')
                    <a href="{{ url('student/activity/' . $section->section_id) }}">Exit</a>
                @else
                    <a href="{{ url('faculty/activity/' . $section->section_id) }}">Exit</a>
                @endif
            </x-button>
        </div>

        <h3 class="font-[Barlow] text-sm sm:text-base mt-2 sm:mt-3 font-medium break-words">
            {{ $activity->user?->name ?? 'Unknown Faculty' }}
            <span class="mx-2">•</span>
            {{
                $activity->created_at->timezone('Asia/Manila')->isCurrentYear()
                ? $activity->created_at->format('M d')
                : $activity->created_at->format('M d, Y')
            }}
        </h3>

        <div class="flex-1 border-t border-gray-400 mt-4 sm:mt-5"></div>

        <div class="my-4 sm:my-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div class="min-w-0">
                    <h4 class="text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Activity Description
                    </h4>
                    <p class="mt-1 text-sm sm:text-base font-[Barlow] font-medium text-gray-800 break-words leading-relaxed">
                        {{ $activity->description ?? '—' }}
                    </p>
                </div>

                <div class="min-w-0">
                    <h4 class="text-[11px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Assigned Simulation Role
                    </h4>
                    <p class="mt-1 text-sm sm:text-base font-[Barlow] font-medium text-gray-800 break-words leading-relaxed">
                        {{ ucfirst($activityRole ?? 'No role assigned') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="flex-1 border-t border-gray-400"></div>
    </div>

    @if (Auth::user()->role === 'faculty')
        <div class="my-4 sm:my-6">
            <h2 class="font-[Poppins] text-xl sm:text-2xl font-medium break-words">
                Students Recently Turned In
            </h2>
        </div>

        <div class="w-full overflow-x-auto">
            <x-table id="" :headers="['Name', 'Role', 'Date Submitted']" align="text-center">
                @php $count = 1; @endphp
                @foreach ($submissions as $submission)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3 min-w-[220px]">
                                <span class="flex-shrink-0">{{ $count++ . '.' }}</span>

                                <img
                                    src="{{ asset('storage/profile_images/' . $submission->user?->profile_image) }}"
                                    alt="{{ $submission->user->name }}"
                                    class="w-10 h-10 rounded-full object-cover flex-shrink-0"
                                >

                                <span class="text-left break-words">
                                    {{ $submission->user->name ?? 'Unknown Student' }}
                                </span>
                            </div>
                        </td>

                        <td class="px-4 py-2 text-center whitespace-nowrap">
                            {{ $submission->user->role_name ?? 'Student' }}
                        </td>

                        <td class="px-4 py-2 text-center whitespace-nowrap">
                            {{
                                \Carbon\Carbon::parse($submission->submitted_at)->isCurrentYear()
                                ? $submission->created_at->format('M d \\a\\t h:i A')
                                : $submission->created_at->format('M d, Y \\a\\t h:i A')
                            }}
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </div>
    @endif
</div>