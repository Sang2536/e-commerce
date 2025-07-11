@props([
    'id' => '',
    'name' => '',
    'label' => '',
    'options' => [],
    'selected' => null
])

<div>
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $label }}</label>
    @endif
    <select id="{{ $id }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white']) }}>
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" @selected($value == $selected)> {{ $text }} </option>
        @endforeach
    </select>
</div>
