<x-layouts :title="'Section | RestauSim'">
<div class="flex-1 overflow-y-auto p-5 bg-white">

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-5">
        @foreach ($sections as $section)
                <div class="h-[259px] border border-black rounded-[10px] p-5 flex flex-col justify-between hover:shadow-lg hover:-translate-y-0.5 transition">
                    <a href="{{ url('student/archived/Activity/' .  $section->section_id)  }}">
                    <div>
                        <div class="text-lg font-bold mb-2 ">{{ $section->class_name }}</div>
                        <div class="text-sm mb-1 hover:underline">{{ $section->section_name }}</div>
                    </div>
                    </a>
                    <div class="text-sm text-black">
                        Instructor:
                        <span class="font-bold">
                            {{ $section->instructor?->name ?? 'Unknown' }}
                        </span>
                    </div>
                </div>
        @endforeach
    </div>

</div>

<script>

</script>

</x-layouts>

