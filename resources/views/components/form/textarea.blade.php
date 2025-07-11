@props([
    'id' => '',
    'name',
    'label' => '',
    'value' => '',
    'rows' => 4,
    'placeholder' => '',
])

<div>
    @if ($label)
        <label for="{{ $id ?: $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
        </label>
    @endif

    <textarea
        id="{{ $id ?: $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white']) }}
    >{{ old($name, $value) }}</textarea>
</div>
