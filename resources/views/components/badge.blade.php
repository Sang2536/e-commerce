@props(['color' => 'gray'])

@php
    $baseClass = 'inline-flex rounded-full px-2 text-xs font-semibold leading-5';
    $colors = [
        'green' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'red' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        'blue' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'gray' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
    ];
@endphp

<span class="{{ $baseClass }} {{ $colors[$color] ?? $colors['gray'] }}">
    {{ $slot }}
</span>
