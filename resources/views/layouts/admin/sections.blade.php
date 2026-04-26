<x-layouts>
    <div class="flex-1 p-6 overflow-y-auto">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Sections</h1>
                <p class="text-sm text-gray-500">
                    Manage all class sections
                </p>
            </div>

        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="overflow-x-auto">
                <x-table :headers="['Section', 'Class Name', 'Created By', 'Class Code', 'Share Code', 'Status', 'Date Created']">
                    @forelse ($sections as $section)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Section -->
                                <td class="px-4 py-3 font-medium text-gray-800 text-sm">
                                    {{ $section->section_name }}
                                </td>

                                <!-- Class -->
                                <td class="px-4 py-3 text-gray-600 text-sm">
                                    {{ $section->class_name }}
                                </td>

                                <!-- Created By -->
                                <td class="px-4 py-3">
                                    <div class="flex flex-row">
                                        <img src="{{ asset('storage/profile_images/' . $section->user->profile_image) }}"
                                        alt="{{ $section->user->name }}" class="w-10 h-10 rounded-full object-cover border">
                                        <div class="flex flex-col ml-2">
                                        <span class="font-medium text-gray-800 text-sm">
                                            {{ $section->user->name ?? 'Unknown' }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $section->user->email ?? '' }}
                                        </span>
                                    </div>
                                    </div>
                                </td>

                                <!-- Class Code -->
                                <td class="px-4 py-3 font-mono text-xs">
                                    {{ $section->class_code }}
                                </td>

                                <!-- Share Code -->
                                <td class="px-4 py-3 font-mono text-xs">
                                    {{ $section->share_code ?? '—' }}
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3">
                                    @if ($section->is_archived)
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-600">
                                            Archived
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-600">
                                            Active
                                        </span>
                                    @endif
                                </td>

                                <!-- Created -->
                                <td class="px-4 py-3 text-gray-800 text-sm">
                                    {{
                                        $section->created_at->isCurrentYear()
                                        ? $section->created_at->format('M d \\a\\t h:i A')
                                        : $section->created_at->format('M d, Y \\a\\t h:i A')
                                    }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                    No sections found.
                                </td>
                            </tr>
                        @endforelse
                </x-table>
            </div>
        </div>
        <div class="mt-4 flex justify-center space-x-2">
            @if ($sections->onFirstPage())
                <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded text-sm cursor-not-allowed">Previous</span>
            @else
                <a href="{{ $sections->previousPageUrl() }}" class="px-3 py-1 bg-gray-200 text-black rounded text-sm hover:bg-gray-300">Previous</a>
            @endif

            <span class="px-5 py-1 bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] rounded text-sm">
                Page {{ $sections->currentPage() }} of {{ $sections->lastPage() }}
            </span>

            @if ($sections->hasMorePages())
                <a href="{{ $sections->nextPageUrl() }}" class="px-3 py-1 bg-gray-200 text-black rounded text-sm hover:bg-gray-300">Next</a>
            @else
                <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded text-sm cursor-not-allowed">Next</span>
            @endif
        </div>
    </div>
</x-layouts>
