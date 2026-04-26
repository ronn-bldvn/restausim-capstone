@props([
    'action',
    'method' => 'POST',
])

<form action="{{ $action }}" method="{{ strtolower($method) === 'get' ? 'GET' : 'POST' }}" {{ $attributes }}>
    @csrf
    @if(!in_array(strtoupper($method), ['GET', 'POST']))
        @method($method)
    @endif

    {{ $slot }}
</form>
