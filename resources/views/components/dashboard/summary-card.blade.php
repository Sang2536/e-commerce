@props(['title', 'value', 'icon', 'color' => 'gray'])

<div class="bg-white p-4 rounded-lg shadow">
    <div class="flex items-center">
        <div class="flex-shrink-0 bg-{{ $color }}-500 rounded-md p-3">
            {!! $icon !!}
        </div>
        <div class="ml-4">
            <h4 class="text-lg font-semibold text-gray-500">{{ $title }}</h4>
            <div class="text-2xl font-bold">{{ $value }}</div>
        </div>
    </div>
</div>
