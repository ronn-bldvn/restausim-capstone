<div>
    @if($logs->count())
        <div class="px-3 pt-4 pb-3">

            {{-- Section Header --}}
            <div class="flex items-center gap-2 mb-3 px-1">
                <div class="w-1 h-4 rounded-full bg-gradient-to-b from-blue-400 to-blue-600"></div>
                <span class="text-xs font-bold tracking-widest uppercase text-white/50 font-['Bricolage_Grotesque']">
                    Log History
                </span>
            </div>
            

            
            {{-- Log Items --}}
            <div class="space-y-1.5">
                @foreach($logs as $log)
                    @php
                        $details = $this->formatDetails($log);
                        $message = $this->formatLog($log);

                        // Determine icon + accent color based on log type keyword
                        $isPayment = str_contains(strtolower($message), 'payment');
                        $isDiscount = str_contains(strtolower($message), 'discount');

                        $accentColor = $isPayment
                            ? 'from-emerald-500/20 to-emerald-500/5 border-emerald-500/20'
                            : ($isDiscount
                                ? 'from-amber-500/20 to-amber-500/5 border-amber-500/20'
                                : 'from-blue-500/20 to-blue-500/5 border-blue-500/20');

                        $dotColor = $isPayment
                            ? 'bg-emerald-400'
                            : ($isDiscount ? 'bg-amber-400' : 'bg-blue-400');

                        $textAccent = $isPayment
                            ? 'text-emerald-300'
                            : ($isDiscount ? 'text-amber-300' : 'text-blue-300');
                    @endphp

                    <div class="group relative rounded-lg border bg-gradient-to-br {{ $accentColor }} px-3 py-2.5 transition-all duration-200 hover:brightness-110 cursor-default">

                        {{-- Dot indicator --}}
                        <span class="absolute left-3 top-3.5 w-1.5 h-1.5 rounded-full {{ $dotColor }} opacity-80"></span>

                        {{-- Message --}}
                        <div class="pl-4 text-xs font-semibold text-white/90 leading-snug break-words">
                            {{ $message }}
                        </div>

                        {{-- Details row --}}
                        @if($details)
                            <div class="pl-4 mt-0.5 text-[10px] {{ $textAccent }} break-words font-medium">
                                {{ $details }}
                            </div>
                        @endif

                        {{-- Timestamp --}}
                        <div class="pl-4 mt-1 text-[10px] text-white/30">
                            {{ $log->created_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Load More --}}
            @if($totalLogs > $limit)
                <div class="mt-3 text-center">
                    <button
                        wire:click="loadMore"
                        wire:loading.attr="disabled"
                        class="group inline-flex items-center gap-1.5 rounded-md border border-white/10 bg-white/5 px-3 py-1.5 text-[11px] font-semibold text-white/50 transition-all duration-200 hover:bg-white/10 hover:text-white/80 hover:border-white/20"
                    >
                        <span wire:loading.remove wire:target="loadMore">Load More</span>
                        <span wire:loading wire:target="loadMore">Loading...</span>
                        <svg wire:loading.remove wire:target="loadMore" class="w-3 h-3 opacity-60 group-hover:translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
            @endif

        </div>
    @else
        {{-- Empty State --}}
        <div class="px-4 py-8 flex flex-col items-center text-center gap-2">
            <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center">
                <svg class="w-4 h-4 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <span class="text-xs text-white/20 font-medium">No activity yet</span>
        </div>
    @endif
</div>
