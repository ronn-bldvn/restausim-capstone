<x-layouts :title="'Join Class | RestauSim'">

    <!-- Full-screen gradient background and centered card -->
    <div class="min-h-screen flex items-center justify-center p-4 w-full font-[Barlow]">

        <div class="bg-gray-100 rounded-2xl shadow-2xl p-8 text-center max-w-md w-full">

            <!-- Icon -->
            <div class="mb-6">
                <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto">
                    <i class="fa-solid fa-users text-[#0D0D54] text-3xl"></i>
                </div>
            </div>

            <!-- Section Info -->
            <h1 class="text-2xl font-bold text-gray-800 mb-1">{{ $section->class_name }}</h1>
            <p class="text-lg text-gray-600 mb-2">{{ $section->section_name }}</p>

            <p class="text-sm text-gray-500 mb-6">
                <i class="fa-solid fa-user-tie mr-1"></i>
                Instructor: {{ $section->user?->name ?? 'Unknown' }}
            </p>

            <div class="border-t border-gray-200 my-6"></div>

            @if($isMember)
                <!-- Already a Member -->
                <div class="mb-6">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <i class="fa-solid fa-check-circle text-green-500 text-2xl mb-2"></i>
                        <p class="text-green-700 font-semibold">You're already a member!</p>
                    </div>
                    <a href="{{ route('student.activity', ['section_id' => $section->section_id]) }}"
                       class="inline-block w-full bg-[#0D0D54] hover:bg-[#0A0A45] text-white font-bold py-3 px-6 rounded-lg transition">
                        <i class="fa-solid fa-arrow-right mr-2"></i>Activities
                    </a>
                </div>
            @else
                <!-- Join Section -->
                <div class="mb-6">
                    <p class="text-gray-600 mb-4">Click the button below to join this section</p>
                    <form method="POST" action="{{ route('section.join.submit', $section->share_code) }}">
                        @csrf
                        <button type="submit"
                            class="w-full bg-[#0D0D54] hover:bg-[#0A0A45] text-white font-bold py-3 px-6 rounded-lg transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-user-plus mr-2"></i>Join Section
                        </button>
                    </form>
                </div>

                {{-- <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-[#EA7C69]">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    By joining, you'll get access to all activities and resources in this section.
                </div> --}}
            @endif

            <!-- Footer -->
            <div class="mt-6 text-xs text-gray-500">
                <p>Invite Code: <span class="font-bold text-gray-700">{{ $section->share_code }}</span></p>
            </div>
        </div>
    </div>

</x-layouts>
