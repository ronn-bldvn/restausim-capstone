<x-layouts :title="'Section | RestauSim'">
    <div class="flex-1 overflow-y-auto p-5 bg-white">

        <x-alert type="success" :message="session('success')" />
        <x-alert type="error1" :message="session('error')" />

        <div class="flex justify-end mb-5 gap-3">
            <x-button id="openJoinSection">
                <i class="fa-solid fa-user-plus mr-2 mt-0.5" style="font-size:18px"></i>
                Join Section
            </x-button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-5">
            @foreach ($sections as $section)
                <div class="relative h-[259px] border border-black rounded-[10px] p-5 flex flex-col justify-between hover:shadow-lg hover:-translate-y-0.5 transition overflow-hidden">

                    <!-- Full card clickable -->
                    <a
                        href="{{ url('student/activity/' . $section->section_id) }}"
                        class="absolute inset-0 z-10"
                        aria-label="Open section {{ $section->class_name }} - {{ $section->section_name }}"
                    ></a>

                    <!-- Content -->
                    <div class="relative z-0">
                        <div class="text-lg font-bold mb-2">
                            {{ $section->class_name }}
                        </div>
                        <div class="text-sm mb-1">
                            {{ $section->section_name }}
                        </div>
                    </div>

                    <div class="relative z-0 text-sm text-black">
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

{{-- @include('partials.modals.student.pre-assessment-quiz') --}}
@include('partials.modals.student.join-section')