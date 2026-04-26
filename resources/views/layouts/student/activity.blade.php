<x-layouts :title="$section->class_name">

    <div class="flex-1">
        <x-alert type="success" :message="session('success')" />
        <div class="rounded-xl h-full shadow-sm">
            <x-section :section="$section" :activity="null" variant="exit-student" />
            @if($feed->count())
                <div class="flex flex-col gap-3 p-6">

                    @foreach($feed as $item)

                        {{-- ----------------------- --}}
                        {{-- ANNOUNCEMENT TEMPLATE --}}
                        {{-- ----------------------- --}}
                        @if($item->type === 'announcement')
                            @php $announcement = $item; @endphp
                                <div
                                    class="border border-gray-200 rounded-xl p-5 mb-6 bg-white shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center space-x-4">
                                            <img src="{{ asset('storage/profile_images/' . $announcement->user?->profile_image) }}"
                                                alt="profile" class="w-11 h-11 rounded-full object-cover border border-gray-100">
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">
                                                    {{ $announcement->user?->name ?? 'Unknown Faculty' }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $announcement->created_at->timezone('Asia/Manila')->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-[15px] text-gray-700 leading-relaxed mb-5 px-1">
                                        {!! nl2br(e($announcement->content)) !!}
                                    </div>
                                    @if($announcement->attachments->count() > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            @foreach($announcement->attachments as $attachment)
                                                @php
                                                    $isYoutube = $attachment->type === 'youtube';
                                                    $ytId = null;
                                                    // 1. Extract YouTube ID
                                                    if ($isYoutube) {
                                                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $attachment->url, $match);
                                                        $ytId = $match[1] ?? null;
                                                    }
                                                    $displayName = $attachment->title;
                                                    if (!$displayName) {
                                                        $host = parse_url($attachment->url, PHP_URL_HOST);
                                                        $displayName = $host ? str_replace('www.', '', $host) : 'Link';
                                                    }
                                                @endphp
                                                <a href="{{ $attachment->type === 'file' ? asset('storage/' . $attachment->url) : $attachment->url }}"
                                                    target="_blank"
                                                    class="group flex items-center border border-gray-200 rounded-lg overflow-hidden hover:bg-gray-50 transition-all bg-white shadow-sm h-20">
                                                    {{-- Visual Side (Icon or Thumbnail) --}}
                                                    <div
                                                        class="w-28 h-full bg-gray-100 flex items-center justify-center flex-shrink-0 border-r relative overflow-hidden">
                                                        @if($isYoutube && $ytId)
                                                            <img src="https://img.youtube.com/vi/{{ $ytId }}/mqdefault.jpg"
                                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                                            <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                                                                <i class="fab fa-youtube text-white text-xl drop-shadow"></i>
                                                            </div>
                                                        @elseif($attachment->type === 'link')
                                                            <div class="flex flex-col items-center">
                                                                <i class="fas fa-link text-blue-500 text-lg"></i>
                                                                <span class="text-[9px] text-gray-400 font-bold uppercase mt-1">Link</span>
                                                            </div>
                                                        @else
                                                            <div class="flex flex-col items-center">
                                                                <i class="far fa-file-alt text-gray-400 text-xl"></i>
                                                                <span class="text-[9px] text-gray-400 font-bold uppercase mt-1">File</span>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- Text Side --}}
                                                    <div class="px-4 py-2 truncate flex-1 min-w-0">
                                                        <p
                                                            class="text-[13px] font-semibold text-gray-800 truncate leading-tight group-hover:text-blue-600 transition-colors">
                                                            {{ $displayName }}
                                                        </p>
                                                        @php
                                                            if ($attachment->type === 'file') {
                                                                $extension = strtoupper(pathinfo($attachment->title, PATHINFO_EXTENSION));
                                                            }
                                                        @endphp

                                                        @if($attachment->type === 'file')
                                                            <p class="text-[11px] text-gray-400 mt-0.5">
                                                                {{ $extension }}
                                                            </p>
                                                        @else
                                                            <p class="text-[11px] text-gray-400 truncate mt-0.5">
                                                                {{ $attachment->url }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                        @endif

                        {{-- --------------------- --}}
                        {{-- ACTIVITY TEMPLATE --}}
                        {{-- --------------------- --}}
                        @if($item->type === 'activity')
                            <div
                                class="border rounded-[10px] p-5 flex flex-col justify-between hover:shadow-lg hover:-translate-y-0.5 transition relative">
                                <div class="flex flex-row items justify-between">
                                    <a href="{{ route('student/activity/activity_details', [$section->section_id, $item->activity_id]) }}"
                                        class="flex flex-row items-center flex-grow pr-3">

                                        <img src="{{ asset('storage/profile_images/' . $item->user?->profile_image) }}"
                                            class="w-[40px] h-[40px] rounded-full object-cover">

                                        <div class="ml-4">
                                            <p class="text-base">
                                                {{ $item->user?->name ?? 'Unknown Faculty' }}
                                                uploaded a new activity: {{ $item->name }}
                                            </p>
                                            @php
                                                $created = $item->created_at->timezone('Asia/Manila');
                                                $updated = $item->updated_at?->timezone('Asia/Manila');

                                                $currentYear = now()->year;

                                                $formatCreated = $created->year == $currentYear
                                                    ? $created->format('M d')
                                                    : $created->format('M d, Y');

                                                $formatUpdated = $updated && $updated->year == $currentYear
                                                    ? $updated->format('M d')
                                                    : ($updated ? $updated->format('M d, Y') : null);
                                            @endphp

                                            <p class="text-sm text-gray-600">

                                                {{-- Created --}}
                                                @if($created->greaterThanOrEqualTo(now()->subWeek()))
                                                    {{ $created->diffForHumans() }}
                                                @else
                                                    {{ $formatCreated }}
                                                @endif

                                                {{-- Edited --}}
                                                @if($updated && $updated->ne($item->created_at))
                                                    <span class="ml-1 text-gray-500">
                                                        (Edited:
                                                        @if($updated->greaterThanOrEqualTo(now()->subWeek()))
                                                            {{ $updated->diffForHumans() }}
                                                        @else
                                                            {{ $formatUpdated }}
                                                        @endif
                                                        )
                                                    </span>
                                                @endif
                                            </p>

                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif

                    @endforeach

                </div>
            @else
                <div class="text-center py-10 text-gray-500">
                    No activity or announcements yet for this section.
                </div>
            @endif

        </div>
    </div>

</x-layouts>
