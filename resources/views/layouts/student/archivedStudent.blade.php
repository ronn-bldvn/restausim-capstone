<x-layouts title="Students in {{ $section->class_name }}">
<div class="flex-1 overflow-y-auto">
    <div class="rounded-xl h-full shadow-sm">
        <div class="bg-[#f2f2f2] h-[48px] text-xs flex border-b border-black">
            @include('partials.includes.topnav', ['activity' => null])
        </div>

        <x-table id="" :headers="['Name']">
            @php $count = 1; @endphp
            @foreach ($users as $user)
                @if ($user->role === 'student')
                    <tr class="">
                        <td class="px-4 py-2 flex items-center gap-2">
                            <span>{{ $count++ . '.' }}</span>
                            <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                            <span>{{ $user->name }}</span>
                        </td>
                    </tr>
                @endif
            @endforeach
        </x-table>
    </div>
</div>

</x-layouts>
