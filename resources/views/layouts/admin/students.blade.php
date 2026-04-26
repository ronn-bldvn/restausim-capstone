<x-layouts>
    <div class="flex-1 p-6 overflow-y-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Student List</h1>
                <p class="text-sm text-gray-500">Manages student accounts and information</p>
            </div>

        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 w-full">
            <div class="overflow-x-auto">
                <x-table :headers="['Student', 'Email', 'Username', 'Account Created']" :align="'text-center'">
                    @forelse ($students as $student)
                        <tr class="border-b last:border-b-0 hover:bg-gray-50 transition">
                            <!-- Faculty -->
                            <td class="py-3 pl-6">
                                <div class="flex justify-start items-center gap-3">
                                    <img src="{{ asset('storage/profile_images/' . $student->profile_image) }}"
                                        alt="{{ $student->name }}" class="w-10 h-10 rounded-full object-cover border">
                                    <span class="text-sm font-medium text-gray-800">
                                        {{ $student->name }}
                                    </span>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="py-3 px-4 text-sm text-gray-700 text-center">
                                {{ $student->email }}
                            </td>

                            <!-- Username -->
                            <td class="py-3 px-4 text-sm text-gray-700 text-center">
                                {{ $student->username }}
                            </td>

                            <!-- Account Created -->
                            <td class="py-3 px-4 text-sm text-gray-700 text-center">
                                {{
                                    $student->created_at->isCurrentYear()
                                    ? $student->created_at->format('M d \\a\\t h:i A')
                                    : $student->created_at->format('M d, Y \\a\\t h:i A')
                                }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center">
                                <p class="text-sm text-gray-500">
                                    No faculty accounts found.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </div>
        </div>
        <div class="mt-4 flex justify-center space-x-2">
            @if ($students->onFirstPage())
                <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded text-sm cursor-not-allowed">Previous</span>
            @else
                <a href="{{ $students->previousPageUrl() }}" class="px-3 py-1 bg-gray-200 text-black rounded text-sm hover:bg-gray-300">Previous</a>
            @endif

            <span class="px-5 py-1 bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] rounded text-sm">
                Page {{ $students->currentPage() }} of {{ $students->lastPage() }}
            </span>

            @if ($students->hasMorePages())
                <a href="{{ $students->nextPageUrl() }}" class="px-3 py-1 bg-gray-200 text-black rounded text-sm hover:bg-gray-300">Next</a>
            @else
                <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded text-sm cursor-not-allowed">Next</span>
            @endif
        </div>
    </div>
</x-layouts>
