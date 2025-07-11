<x-admin-layout>
    <x-slot name="header">Quản lý đơn hàng</x-slot>

    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Danh sách đơn hàng</h2>
            <span class="ml-4 px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                {{ $orders->total() }} đơn hàng
            </span>
        </div>
    </div>

    <x-card class="mb-6">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <x-form.input name="search" label="Tìm kiếm" :value="request('search')" placeholder="Tên, email hoặc số điện thoại khách hàng..." />

            <x-form.select name="status" label="Trạng thái">
                <option value="">Tất cả</option>
                <option value="pending" @selected(request('status') === 'pending')>Chờ xử lý</option>
                <option value="processing" @selected(request('status') === 'processing')>Đang xử lý</option>
                <option value="shipped" @selected(request('status') === 'shipped')>Đã giao</option>
                <option value="delivered" @selected(request('status') === 'delivered')>Đã nhận</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Đã hủy</option>
            </x-form.select>

            <x-buttons.button class="md:self-end">Lọc kết quả</x-buttons.button>
        </form>
    </x-card>

    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <x-table.th>ID</x-table.th>
                <x-table.th>Mã đơn</x-table.th>
                <x-table.th>Khách hàng</x-table.th>
                <x-table.th>Email</x-table.th>
                <x-table.th>Điện thoại</x-table.th>
                <x-table.th>Tổng tiền</x-table.th>
                <x-table.th>Trạng thái</x-table.th>
                <x-table.th>Ngày tạo</x-table.th>
                <x-table.th class="text-right">Thao tác</x-table.th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
            @forelse ($orders as $order)
                <tr>
                    <x-table.td>{{ $order->id }}</x-table.td>
                    <x-table.td>{{ $order->order_number }}</x-table.td>
                    <x-table.td>{{ $order->customer->name ?? '[N/A]' }}</x-table.td>
                    <x-table.td>{{ $order->customer->email ?? '[N/A]' }}</x-table.td>
                    <x-table.td>{{ $order->customer->phone ?? '[N/A]' }}</x-table.td>
                    <x-table.td>{{ number_format($order->total) }} đ</x-table.td>
                    <x-table.td>
                        @php
                            $color = match($order->status) {
                                'pending' => 'yellow',
                                'processing' => 'blue',
                                'shipped' => 'indigo',
                                'delivered' => 'green',
                                'cancelled' => 'red',
                                default => 'gray',
                            };
                        @endphp
                        <x-badge :color="$color">
                            {{ ucfirst(__($order->status)) }}
                        </x-badge>
                    </x-table.td>
                    <x-table.td>{{ $order->created_at->format('d/m/Y H:i') }}</x-table.td>
                    <x-table.td class="text-right">
                        <x-buttons.group>
                            <x-buttons.link :href="route('admin.orders.show', $order)" icon="eye" />
                            <x-buttons.delete
                                :action="route('admin.orders.destroy', $order)"
                                :confirm="'Bạn có chắc chắn muốn xóa đơn hàng #' . $order->order_number . '?'"
                            />
                        </x-buttons.group>
                    </x-table.td>
                </tr>
            @empty
                <x-table.empty colspan="9" message="Không tìm thấy đơn hàng nào." />
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $orders->links() }}
    </div>
</x-admin-layout>
