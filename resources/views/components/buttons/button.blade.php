@props(['type' => 'submit', 'variant' => 'primary'])
@php
    $classes = match($variant) {
        'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
        default => 'bg-gray-500 text-white',
    };
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "inline-flex items-center px-4 py-2 rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors $classes"]) }}>
    {{ $slot }}
</button>
