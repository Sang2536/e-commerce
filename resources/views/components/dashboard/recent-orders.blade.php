@props(['orders'])

<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
    <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
    </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
    @forelse($orders as $order)
        <tr>
            <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
            <td class="px-6 py-4 text-sm text-gray-500">{{ $order->user->name }}</td>
            <td class="px-6 py-4 text-sm text-gray-500">{{ number_format($order->total, 0, ',', '.') }} đ</td>
            <td class="px-6 py-4">
                <x-status.order :status="$order->status" />
            </td>
            <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            <td class="px-6 py-4 text-sm font-medium">
                <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">Chi tiết</a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Không có đơn hàng nào</td>
        </tr>
    @endforelse
    </tbody>
</table>
