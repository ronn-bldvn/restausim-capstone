<div id="openArchivedStudentModal" class="fixed inset-0 bg-black/50 hidden z-50 items-center justify-center overflow-auto p-4">
    <div class="bg-white p-6 rounded-xl shadow-lg flex flex-col relative w-full max-w-5xl max-h-[90vh]">
        <!-- Close button -->
        <x-button
            variant="closeX"
            type="button"
            class="self-end mb-4 cursor-pointer"
            onclick="document.getElementById('openArchivedStudentModal').classList.add('hidden')">
            ✖
        </x-button>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
            <div class="flex flex-col">
                <span class="text-xl font-semibold">All Archived Students</span>
                <span class="text-green-700">{{ $studentCount }} Students</span>
            </div>

            <div class="flex items-center bg-[#f1f3f6] rounded-[6px] px-2 py-1">
                <span>🔍</span>
                <input
                    type="text"
                    placeholder="Search"
                    class="border-none outline-none bg-transparent pl-1 text-sm"
                />
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-auto">
            <x-table id="" :headers="['Name', 'Email', 'Username', 'Section', 'Actions']">
                @php $count = 1; @endphp
                @foreach ($users as $user)
                    @if ($user->role === 'student')
                        <tr class="text-center">
                            <td class="px-4 py-2 flex items-center gap-2">
                                <span>{{ $count++ . '.' }}</span>
                                <img src="{{ asset('images/placeholder.png') }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                                <span>{{ $user->name }}</span>
                            </td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">{{ $user->username }}</td>
                            <td class="px-4 py-2">placeholder</td>
                            <td class="p-3">
                                <x-button type="button" variant="modal">
                                    <i class="fa-solid fa-archive mr-2 mt-0.5" style="font-size:18px"></i>
                                    Unarchive
                                </x-button>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </x-table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const openBtn = document.getElementById('openArchivedStudents');
        const modal = document.getElementById('openArchivedStudentModal');
        const closeBtn = document.getElementById('closeModalBtn');

        // Open modal
        openBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        });

        // Close modal
        closeBtn.addEventListener("click", () => {
            modal.classList.remove('flex');
            modal.classList.add("hidden");
        });

        // Close when clicking outside modal content
        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.add("hidden");

            }
        });
    });
</script>
