<x-layouts :title="$activity->name . ' | ' . $section->class_name">
    <div class="flex-1">
        <div class="rounded-xl h-full shadow-sm">
            <div class="bg-[#f2f2f2] h-[48px] text-xs flex border-b border-black">
                @include('partials.includes.topnav')
            </div>
            {{-- $activity -> description }} <br> --}}

            {{-- <x-section :section="$section" :activity="null" variant="exit" />--}}
            {{-- <x-activity-card :activity="$activity" :users="$users" :section="$section" /> --}}

            <div class="bg-white flex flex-col md:flex-col rounded-xl items-start px-8 py-6 gap-4 md:gap-0 m-5">
                <div class="flex flex-col w-full">
                    <div class="flex flex-row">
                        <h2 class="font-[Poppins] text-4xl font-semibold">{{ $activity->name }}</h2>
                        <x-button variant="cancel">
                            @if (Auth::user()->role === 'student')
                                <a href="{{ url('student/activity/' . $section->section_id) }}">
                                    Exit
                                </a>
                            @else
                                <a href="{{ url('faculty/archived/Activity/' . $section->section_id) }}">
                                    Exit
                                </a>
                            @endif
                        </x-button>

                    </div>
                    <h3 class="font-[Barlow] text-base mt-3 font-medium">
                        {{ $activity->user?->name ?? 'Unknown Faculty' }}
                        <span class="mx-2">•</span>
                        {{ $activity->created_at->timezone('Asia/Manila')->format('M d') }}
                    </h3>
                    <div class="flex justify-between w-full">

                    </div>
                    <div class="flex-1 border-t border-gray-400 mt-5"></div>
                    <div class="my-6">
                        <span class="font-[Barlow] text-base font-medium leading-relaxed">Activity Description:
                            {{ $activity->description }}</span>
                    </div>
                    <div class="flex-1 border-t border-gray-400"></div>
                </div>
                <div class="my-6">
            <h2 class="font-[Poppins] text-2xl font-medium">Students Recently Turned In</h2>
        </div>

        <div class="w-full">
            <x-table id="" :headers="['Name', 'Role', 'Date Submitted']" align="text-center">
                @php $count = 1; @endphp
                @foreach ($submissions as $submission)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex-shrink-0">{{ $count++ . '.' }}</span>
                                <img src="{{ asset('storage/profile_images/' . $submission->user?->profile_image) }}"
                                    alt="{{ $submission->user->name }}"
                                    class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                                <span class="text-left">{{ $submission->user->name ?? 'Unknown Student' }}</span>
                            </div>
                        </td>

                        <td class="px-4 py-2 text-center">
                            {{ $submission->user->role_name ?? 'Student' }}
                        </td>

                        <td class="px-4 py-2 text-center">
                            {{ \Carbon\Carbon::parse($submission->submitted_at)->format('F d - g:i a') }}
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </div>
            </div>


        </div>
    </div>

    <!-- JS for all cards and modals -->
    <script>


        window.addEventListener('load', () => {
            // Select all cards
            const cards = document.querySelectorAll('[id^="card-"]');
            console.log('Cards found:', cards.length); // Should be 5

            cards.forEach(card => {
                const uid = card.id.split('card-')[1];
                const modal = document.getElementById('modal-' + uid);
                const closeBtn = modal.querySelector('.close-btn');

                // Open modal on card click
                card.addEventListener('click', () => modal.classList.remove('hidden'));

                // Close modal on button click
                closeBtn.addEventListener('click', () => modal.classList.add('hidden'));

                // Close modal if clicking outside modal content
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) modal.classList.add('hidden');
                });
            });
        });
    </script>
</x-layouts>

{{-- {{ $activity -> section-> class_name }} <br>
{{ $activity -> section-> section_name }} <br>
{{ $activity -> name }} <br>
{{ $activity -> code }} <br>
{{ \Carbon\Carbon::parse($activity->due_date)->format('M d, Y h:i A') }}<br>
{{ $activity->created_at->timezone('Asia/Manila')->diffForHumans() }}<br>
{{ $activity -> description }} <br> --}}
