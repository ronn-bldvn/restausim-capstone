<div id="openSeeAllStudentModal" class="fixed inset-0 bg-black/50 hidden z-50 items-center justify-center overflow-auto p-4">
    <div class="bg-white p-6 rounded-xl shadow-lg flex flex-col relative w-full max-w-5xl max-h-[90vh]">
        <!-- Close button -->
        <x-button
            variant="closeX"
            type="button"
            class="self-end mb-4 text-lg"
            onclick="document.getElementById('openSeeAllStudentModal').classList.add('hidden')">
            ✖
        </x-button>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
            <div class="flex flex-col">
                <span class="text-xl font-semibold">All Students</span>
                <span class="text-green-700">{{ $studentCount }} Students</span>
            </div>

            <div class="flex flex-row flex-wrap gap-2 md:gap-4">
                <x-button id="AddStudentBtn" type="button" variant="modal">
                    <i class="fa-solid fa-plus-circle mr-2" style="font-size:18px"></i>Add Students
                </x-button>
                {{-- <x-button type="button" variant="modal">
                    <i class="fa-solid fa-archive mr-2 mt-0.5" style="font-size:18px"></i>Archived Students
                </x-button> walang functionality sa figma --}}
            </div>

            <div class="flex items-center">
                {{-- <span>🔍</span> --}}
                <x-input type="text" name="search" placeholder="Search..."/>
                {{-- <input
                    type="text"
                    placeholder="Search"
                    class="border-none outline-none bg-transparent pl-1 text-sm"
                /> --}}
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-auto">
            <x-table id="" :headers="['Name', 'Email', 'Username', 'Section']">
                @php $count = 1; @endphp
                @foreach ($users as $user)
                    @if ($user->role === 'student')
                        <tr class="text-center">
                            <td class="px-4 py-2 flex items-center justify-center gap-2">
                                <span>{{ $count++ . '.' }}</span>
                                <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                                <span>{{ $user->name }}</span>
                            </td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">{{ $user->username }}</td>
                            <td class="px-4 py-2">
                                {{ $user->sections->first()->section_name ?? 'N/A' }}
                            </td>
                            {{-- <td class="p-3">
                                <div class="flex justify-between">
                                    <x-button type="button" variant="modal">
                                    <i class="fa-solid fa-archive mt-0.5" style="font-size:18px"></i>
                                </x-button>
                                <x-button type="button" variant="modal">
                                    <i class="fa-solid fa-trash mt-0.5" style="font-size:18px; color: red;" ></i>
                                </x-button>
                                </div>
                            </td> --}}
                        </tr>
                    @endif
                @endforeach
            </x-table>
        </div>
    </div>
</div>

{{-- bakit nag-aarchive ng students?, hindi ba dapat ang iarchive is yung created na section? --}}
