@props(['section', 'variant' => 'exit', 'activity', 'share_code' => null])

<div class="bg-white border flex flex-col md:flex-row justify-between rounded-xl items-start px-8 py-6 gap-4 md:gap-0 m-5">

    <div class="flex flex-col gap-1">
        <h2 class="text-4xl font-bold">{{ $section->class_name }}</h2>
        <h3 class="text-2xl font-medium">{{ $section->name }}</h3>
        <h4 class="text-xl font-normal mt-4">{{ $section->section_name }}</h4>
        <h5 class="text-sm font-normal mt-4"><span>Class Code: {{ $section->share_code }}</span></h5>
    </div>

    {{-- Exit button for Faculty Announcement --}}
    @if($variant === 'exit')
        <div class="ml-auto mr-4 flex items-center">
            <a href="{{ url('faculty/announcements/' . $section->section_id) }}">
                <x-button type="button">Exit</x-button>
            </a>
        </div>

    {{-- For creating activities --}}
    @elseif($variant === 'create')
        <div class="flex gap-3">
            <x-button type="button" id="createActivityBtn" class="cursor-not-allowed disabled">
                <i class="fa-solid fa-plus-circle mr-2 mt-0.5" style="font-size:18px"></i>
                Create Activity
            </x-button>

            <a href="{{ url('faculty/section') }}">
                <x-button type="button">Exit</x-button>
            </a>
        </div>

    {{-- For announcements --}}
    @elseif($variant === 'announce')
        <div class="flex gap-3">
            <x-button type="button" id="createAnnounceBtn" class="cursor-not-allowed">
                <i class="fa-solid fa-bullhorn mr-2 mt-0.5" style="font-size:18px"></i>
                Post Announcement
            </x-button>

            @if($activity)

                @if(isset($section))
                    <a href="{{ url('faculty/announcements/' . $section->section_id) }}">
                        <x-button type="button">Exit</x-button>
                    </a>
                @else
                    <a href="{{ url('faculty/archived/Activity/' . ($activity->section_id ?? $activity->id) ) }}">
                        <x-button type="button">Exit</x-button>
                    </a>
                @endif
            @else
                <a href="{{ url('faculty/archivedsection') }}">
                    <x-button type="button">Exit</x-button>
                </a>
            @endif
        </div>

    {{-- Exit for student --}}
    @elseif($variant === 'exit-student')
        <div class="flex gap-3">
            <a href="{{ url('student/section') }}">
                <x-button type="button">Exit</x-button>
            </a>
        </div>
    @endif
</div>
