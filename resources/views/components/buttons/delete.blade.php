@props(['action', 'confirm' => 'Bạn có chắc chắn muốn xoá?'])

<button
    onclick="if (confirm('{{ $confirm }}')) { window.location.href = '{{ $action }}'; }"
    {{ $attributes->merge(['class' => 'text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300']) }}>
    {{ $slot ?? 'Xoá' }}
</button>
