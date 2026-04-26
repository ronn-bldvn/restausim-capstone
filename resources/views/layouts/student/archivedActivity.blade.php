<x-layouts :title="$section->class_name">

<div class="flex-1">
    <x-alert type="success" :message="session('success')" />
    <div class="rounded-xl h-full shadow-sm">
        {{-- <x-section :section="$section" :activity="null" variant="exit-student"/> --}}
        <div
                class="bg-white border flex flex-col md:flex-row justify-between rounded-xl items-start px-8 py-6 gap-4 md:gap-0 m-5">

                <div class="flex flex-col gap-1">
                    <h2 class="text-4xl font-bold">{{ $section->class_name }}</h2>
                    <h3 class="text-2xl font-medium">{{ $section->name }}</h3>
                    <h4 class="text-xl font-normal mt-4">{{ $section->section_name }}</h4>
                </div>

                    <div class="flex gap-3">
                        <a href="{{ url('student/archivedsection') }}">
                            <x-button type="button">Exit</x-button>
                        </a>
                    </div>
            </div>
        @if($feed->count())
            <div class="flex flex-col gap-3 p-6">

                @foreach($feed as $item)

                    {{-- ----------------------- --}}
                    {{-- ANNOUNCEMENT TEMPLATE --}}
                    {{-- ----------------------- --}}
                    @if($item->type === 'announcement')
                        <div class="border rounded-[10px] p-5 hover:shadow-lg transition">
                            <div class="flex flex-row items-center">
                                <img src="{{ asset('storage/profile_images/' . $item->user?->profile_image) }}"
                                    class="w-[40px] h-[40px] rounded-full object-cover">

                                <div class="ml-4">
                                    <p class="text-base font-semibold text-black">
                                        {{ $item->user?->name ?? 'Unknown Faculty' }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ $item->created_at->timezone('Asia/Manila')->diffForHumans() }}
                                        @if($item->updated_at && $item->updated_at->ne($item->created_at))
                                            <span class="mr-2">(Edited: {{ $item->updated_at->timezone('Asia/Manila')->diffForHumans() }})</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 text-sm text-gray-800 leading-relaxed">
                                {!! nl2br(e($item->content)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- --------------------- --}}
                    {{-- ACTIVITY TEMPLATE --}}
                    {{-- --------------------- --}}
                    @if($item->type === 'activity')
                        <div class="border rounded-[10px] p-5 flex flex-col justify-between hover:shadow-lg hover:-translate-y-0.5 transition relative">
                            <div class="flex flex-row items justify-between">
                                <a href="{{ route('student/archived/activity/activity_details', [$section->section_id, $item->activity_id]) }}"
                                class="flex flex-row items-center flex-grow pr-3">

                                    <img src="{{ asset('storage/profile_images/' . $item->user?->profile_image) }}"
                                        class="w-[40px] h-[40px] rounded-full object-cover">

                                    <div class="ml-4">
                                        <p class="text-base">
                                            {{ $item->user?->name ?? 'Unknown Faculty' }}
                                            uploaded a new activity: {{ $item->name }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ $item->created_at->timezone('Asia/Manila')->diffForHumans() }}
                                            @if($item->updated_at && $item->updated_at->ne($item->created_at))
                                                <span class="mr-2">(Edited: {{ $item->updated_at->timezone('Asia/Manila')->diffForHumans() }})</span>
                                            @endif
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif

                @endforeach

            </div>
        @else
            <div class="text-center py-10 text-gray-500">
                No activity or announcements yet for this section.
            </div>
        @endif

    </div>
</div>

</x-layouts>
