@props(['status'])

@php
    $statuses = [
        'pending' => ['label' => 'Chờ xử lý', 'color' => 'bg-yellow-100 text-yellow-800'],
        'processing' => ['label' => 'Đang xử lý', 'color' => 'bg-blue-100 text-blue-800'],
        'shipping' => ['label' => 'Đang giao hàng', 'color' => 'bg-indigo-100 text-indigo-800'],
        'completed' => ['label' => 'Hoàn thành', 'color' => 'bg-green-100 text-green-800'],
        'cancelled' => ['label' => 'Đã hủy', 'color' => 'bg-red-100 text-red-800'],
    ];

    $status = strtolower($status);
    $label = $statuses[$status]['label'] ?? ucfirst($status);
    $color = $statuses[$status]['color'] ?? 'bg-gray-100 text-gray-800';
@endphp

<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
    {{ $label }}
</span>
