@props(['current', 'last'])

<div class="grid grid-cols-2 gap-4">
    <div class="bg-green-50 p-4 rounded-lg">
        <h4 class="text-sm font-medium text-green-800">Tháng này</h4>
        <div class="mt-2 text-3xl font-bold text-green-900">{{ number_format($current, 0, ',', '.') }} đ</div>
    </div>
    <div class="bg-blue-50 p-4 rounded-lg">
        <h4 class="text-sm font-medium text-blue-800">Tháng trước</h4>
        <div class="mt-2 text-3xl font-bold text-blue-900">{{ number_format($last, 0, ',', '.') }} đ</div>
        @if($last > 0)
            <div class="text-sm mt-2">
                @php
                    $diff = $current - $last;
                    $rate = number_format(abs($diff / $last) * 100, 1);
                @endphp
                @if($diff > 0)
                    <span class="text-green-700">+{{ $rate }}%</span> so với tháng trước
                @elseif($diff < 0)
                    <span class="text-red-700">-{{ $rate }}%</span> so với tháng trước
                @else
                    <span class="text-gray-700">0%</span> so với tháng trước
                @endif
            </div>
        @endif
    </div>
</div>
