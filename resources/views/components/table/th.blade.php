@props(['align' => 'left'])

<th scope="col" class="px-3 py-3.5 text-sm font-semibold text-gray-900 dark:text-gray-200 text-{{ $align }}">
    {{ $slot }}
</th>
