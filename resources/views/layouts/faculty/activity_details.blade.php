<x-layouts :title="$activity->name . ' | ' . $section->class_name">
<div class="flex-1">
    <div class="rounded-xl h-full shadow-sm">

        <div class="bg-[#f2f2f2] h-[48px] text-xs flex border-b border-black">
            @include('partials.includes.topnav', ['activity' => $activity ?? null])
        </div>

        <x-activity-card
            :activity="$activity"
            :users="$users"
            :section="$section"
            :submissions="$submissions"
            :activityRole="$activityRole"
        />

    </div>
</div>

</x-layouts>

{{-- {{ $activity -> section-> class_name }} <br>
{{ $activity -> section-> section_name }} <br>
{{ $activity -> name }} <br>
{{ $activity -> code }} <br>
{{ \Carbon\Carbon::parse($activity->due_date)->format('M d, Y h:i A') }}<br>
{{ $activity->created_at->timezone('Asia/Manila')->diffForHumans() }}<br>
{{ $activity -> description }} <br> --}}

