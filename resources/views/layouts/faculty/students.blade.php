<x-layouts :title="'Students Joined | RestauSim'">

    <div class="flex-1 overflow-y-auto p-5 bg-white">
        <!-- Page Header -->
        <div class="flex justify-between items-center px-5 mb-6">

            <!-- Left: Title + Subtitle -->
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Students Joined on Sections</h1>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-5 mt-5">
            @foreach ($students as $student)
                @if ($student->role === 'student')
                    <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition">
                        <div class="flex flex-row">
                            <img src="{{ asset('storage/profile_images/' . $student->profile_image) }}" alt="Avatar"
                                class="w-10 h-10 rounded-full object-cover">
                            <div class="flex flex-col justify-center ml-2 gap-1">
                                <span class="text-sm font-semibold text-gray-800">{{ $student->name }}</span>
                                <span class="text-xs text-gray-500">{{ $student->email }}</span>
                            </div>
                        </div>
                        <div class="mt-3 space-y-2">
                            <span class="text-sm">Sections Joined:</span>

                            @forelse ($student->sections as $s)
                                <span class="font-[Barlow] inline-block text-xs px-2 py-1 rounded-md bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-black font-semibold">
                                    {{ $s->section_name }} - {{ $s->class_name }}
                                </span>
                            @empty
                                <span class="text-gray-500 text-sm">N/A</span>
                            @endforelse
                        </div>

                    </div>

                @endif
            @endforeach
        </div>

    </div>

</x-layouts>
