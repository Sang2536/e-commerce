@props(['class' => ''])
<td {{ $attributes->merge(['class' => 'px-3 py-4 text-sm text-gray-900 dark:text-gray-100 ' . $class]) }}>
    {{ $slot }}
</td>
