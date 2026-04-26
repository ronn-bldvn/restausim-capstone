@props([
    'activity',
    'users',
    'role',
    'background',
    'profile',
    'description',
    'features',
    'id',
    'hasSubmission' => false,
    'hasInProgress' => false,
])

{{-- Card --}}
<div id="{{ $id }}"
    class="w-[319px] h-[335px] rounded-lg border overflow-hidden bg-[#C7C7C7] flex flex-col cursor-pointer hover:shadow-lg transition"
    data-role="{{ $role }}"
    data-has-submission="{{ $hasSubmission ? 'true' : 'false' }}"
    data-has-in-progress="{{ $hasInProgress ? 'true' : 'false' }}">

    <div>
        <img src="{{ asset($background) }}" alt="">
    </div>

    <div class="text-sm font-bold mx-3 mt-2">
        Simulation Role: {{ ucfirst($role) }}
    </div>

    <div class="font-[Roboto] text-xs mx-3 mt-3">
        Description: {{ $description }}
    </div>

    <div class="font-[Barlow] text-xs font-medium mx-3 mt-2">
        Tools Accessed in Simulation
    </div>

    <div class="flex flex-row flex-wrap ml-3 my-2 gap-3">
        @foreach ($features as $feature)
            <x-capsule :label="$feature" />
        @endforeach
    </div>

    <div class="mt-auto px-3 pb-3">
        @if($hasSubmission)
            <span class="inline-flex items-center rounded-full bg-green-100 text-green-700 text-[11px] px-3 py-1 font-semibold">
                <i class="fa-solid fa-circle-check mr-1"></i>
                Submitted
            </span>
        @elseif($hasInProgress)
            <span class="inline-flex items-center rounded-full bg-yellow-100 text-yellow-700 text-[11px] px-3 py-1 font-semibold">
                <i class="fa-solid fa-clock mr-1"></i>
                In Progress
            </span>
        @else
            <span class="inline-flex items-center rounded-full bg-blue-100 text-blue-700 text-[11px] px-3 py-1 font-semibold">
                <i class="fa-solid fa-play mr-1"></i>
                Not Started
            </span>
        @endif
    </div>
</div>

{{-- Modal --}}
@php
    $modalId = 'modal-' . (is_string($id) && str_contains($id, '-') ? explode('-', $id)[1] : $id);
@endphp

<div id="{{ $modalId }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white border border-black rounded-lg p-6 relative w-full max-w-[900px] max-h-[90vh] overflow-y-auto">

        {{-- Modal Header --}}
        <button type="button" class="close-btn text-xl font-black absolute top-2 right-2 text-gray-700 hover:text-black">
            X
        </button>

        <div class="flex flex-col">
            <span class="font-['Barlow'] font-extrabold text-2xl sm:text-3xl break-words">
                {{ $activity?->name }}
            </span>
            <span class="font-['Barlow'] mt-1 text-sm sm:text-base">
                Role: {{ ucfirst($role) }}
            </span>
        </div>

        {{-- Modal Body --}}
        <div class="flex flex-col lg:flex-row mt-4 gap-4">
            {{-- Left: Image --}}
            <div class="flex justify-center w-full lg:w-1/2">
                <img src="{{ asset($background) }}"
                    alt="Activity Background"
                    class="w-full h-auto max-h-[450px] rounded-lg object-cover border">
            </div>

            {{-- Right: Details --}}
            <div class="flex-1">
                <div class="flex flex-row items-center ml-2">
                    <div class="flex justify-center items-center">
                        <img src="{{ asset('storage/profile_images/' . $activity->user?->profile_image) }}"
                            alt="Profile Image"
                            class="w-12 h-12 rounded-full object-cover border-4 border-gray-200 shadow-sm">
                    </div>

                    <div class="flex flex-col ml-4">
                        <h2 class="mb-1 font-medium text-gray-800">
                            {{ $activity->user?->name ?? 'Unknown Faculty' }}
                        </h2>
                        <span class="text-gray-600 font-medium capitalize">
                            {{ ucfirst($activity->user?->role) }}
                        </span>
                    </div>
                </div>

                <div class="border-t border-gray-300 my-3"></div>

                {{-- Tools --}}
                <div>
                    <div class="font-[Barlow] font-medium mb-2">Tools Accessed in Simulation</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach ($features as $feature)
                            <x-capsule :label="$feature" size="xs" />
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-gray-300 my-3"></div>

                {{-- Description --}}
                <div>
                    <span class="block text-sm text-justify">
                        {{ $activity?->description }}
                    </span>
                </div>

                <div class="border-t border-gray-300 my-3"></div>

                {{-- Status --}}
                <div class="mb-4">
                    <div class="font-[Barlow] font-medium mb-2">Status</div>

                    @if($hasSubmission)
                        <div class="rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                            This simulation role has already been submitted.
                        </div>
                    @elseif($hasInProgress)
                        <div class="rounded-lg bg-yellow-50 border border-yellow-200 px-4 py-3 text-sm text-yellow-700">
                            You already have an in-progress session for this role. Continuing will resume it.
                        </div>
                    @else
                        <div class="rounded-lg bg-blue-50 border border-blue-200 px-4 py-3 text-sm text-blue-700">
                            You have not started this simulation role yet.
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-center">
                    @if($hasSubmission)
                        <x-button variant="btnGradientv1" type="button" class="already-submitted-btn opacity-70 cursor-not-allowed" disabled>
                            <i class="fa-solid fa-circle-check mr-2"></i>
                            Already Submitted
                        </x-button>
                    @elseif($hasInProgress)
                        <x-button variant="btnGradientv1" type="button" class="start-simulation-btn">
                            <i class="fa-solid fa-rotate-right mr-2"></i>
                            Resume Simulation
                        </x-button>
                    @else
                        <x-button variant="btnGradientv1" type="button" class="start-simulation-btn">
                            <i class="fa-solid fa-play mr-2"></i>
                            Start Simulation
                        </x-button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>