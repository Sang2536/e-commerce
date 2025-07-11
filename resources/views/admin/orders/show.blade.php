<x-admin-layout>
    <x-slot name="header">Chi tiết đơn hàng #{{ $order->order_number }}</x-slot>

    <x-card class="mb-6">
        <h2 class="text-lg font-semibold mb-2">Thông tin khách hàng</h2>
        <p><strong>Họ tên:</strong> {{ $order->customer->name }}</p>
        <p><strong>Email:</strong> {{ $order->customer->email }}</p>
        <p><strong>Điện thoại:</strong> {{ $order->customer->phone }}</p>
    </x-card>

    <x-card class="mb-6">
        <h2 class="text-lg font-semibold mb-2">Thông tin giao hàng</h2>
        <p><strong>Người nhận:</strong> {{ $order->shipping_name }}</p>
        <p><strong>Email:</strong> {{ $order->shipping_email }}</p>
        <p><strong>Điện thoại:</strong> {{ $order->shipping_phone }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}, {{ $order->shipping_state }}, {{ $order->shipping_city }} - {{ $order->shipping_zip_code }}</p>
        @if($order->notes)
            <p><strong>Ghi chú:</strong> {{ $order->notes }}</p>
        @endif
    </x-card>

    <x-card class="mb-6">
        <h2 class="text-lg font-semibold mb-2">Chi tiết đơn hàng</h2>
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <x-table.th>Sản phẩm</x-table.th>
                <x-table.th>Số lượng</x-table.th>
                <x-table.th>Đơn giá</x-table.th>
                <x-table.th>Thành tiền</x-table.th>
            </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($order->items as $item)
                <tr>
                    <x-table.td>{{ $item->product->name ?? 'Sản phẩm đã xoá' }}</x-table.td>
                    <x-table.td>{{ $item->quantity }}</x-table.td>
                    <x-table.td>{{ number_format($item->price) }} đ</x-table.td>
                    <x-table.td>{{ number_format($item->total) }} đ</x-table.td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-card>

    <x-card>
        <h2 class="text-lg font-semibold mb-2">Tổng thanh toán</h2>
        <p><strong>Tạm tính:</strong> {{ number_format($order->subtotal) }} đ</p>
        <p><strong>Thuế:</strong> {{ number_format($order->tax) }} đ</p>
        <p><strong>Phí vận chuyển:</strong> {{ number_format($order->shipping_cost) }} đ</p>
        <p class="text-lg font-bold"><strong>Tổng cộng:</strong> {{ number_format($order->total) }} đ</p>
    </x-card>
</x-admin-layout>
