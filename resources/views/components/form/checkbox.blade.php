@props([
    'id' => $name ?? '',
    'name',
    'label' => '',
    'checked' => false,
])

@php
    $isChecked = old($name, $checked) ? 'checked' : '';
@endphp

<div class="flex items-center space-x-2">
    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="checkbox"
        value="1"
        {{ $isChecked }}
        {{ $attributes->merge(['class' => 'rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500']) }}
    >
    @if($label)
        <label for="{{ $id }}" class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</label>
    @endif
</div>
