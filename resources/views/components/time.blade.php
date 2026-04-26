@props([
    'hour' => null,
    'minute' => null,
    'ampm' => null,
])

<div id="timePicker" class="bg-white rounded-xl p-5 shadow-lg mt-5 hidden animate-slideIn">
    <label class="block mb-3 font-semibold text-gray-800">Due Time <span class="text-red-500 ml-0.5">*</span></label>
    <div class="flex items-center justify-center gap-5">
        <input
            type="text"
            id="hourValue"
            maxlength="2"
            value="{{ $hour }}"
            class="text-2xl font-semibold p-3 w-14 text-center border rounded-md">

        <div class="text-2xl font-semibold text-gray-800">:</div>

        <input
            type="text"
            id="minuteValue"
            maxlength="2"
            value="{{ $minute }}"
            class="text-2xl font-semibold p-3 w-14 text-center border rounded-md">

        <div
            id="amPm"
            class="bg-gray-100 border-2 border-gray-200 rounded-lg px-4 py-2 text-lg font-semibold cursor-pointer transition">
            {{ $ampm }}
        </div>
    </div>
</div>
