<div id="activities-container">
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="divide-y divide-slate-100">
            @foreach ($latestActivities as $activity)
                <div class="p-4 flex items-center justify-between hover:bg-slate-50">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-slate-900">{{ $activity->name }}</h4>
                            <p class="text-xs text-slate-500">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-4 flex justify-center space-x-2 ajax-pagination">
        @if ($latestActivities->onFirstPage())
            <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded text-sm cursor-not-allowed">Previous</span>
        @else
            <a href="{{ $latestActivities->previousPageUrl() }}" class="px-3 py-1 bg-gray-200 text-black rounded text-sm hover:bg-gray-300">Previous</a>
        @endif

        <span class="px-5 py-1 bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] rounded text-sm">
            Page {{ $latestActivities->currentPage() }} of {{ $latestActivities->lastPage() }}
        </span>

        @if ($latestActivities->hasMorePages())
            <a href="{{ $latestActivities->nextPageUrl() }}" class="px-3 py-1 bg-gray-200 text-black rounded text-sm hover:bg-gray-300">Next</a>
        @else
            <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded text-sm cursor-not-allowed">Next</span>
        @endif
    </div>
</div>
