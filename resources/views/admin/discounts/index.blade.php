<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">Danh sách mã giảm giá</h2>
    </x-slot>

    <div class="py-4">
        <x-button.link href="{{ route('admin.discounts.create') }}" class="mb-4">
            + Thêm mã giảm giá
        </x-button.link>

        <x-table>
            <x-slot name="head">
                <x-table.th>Mã</x-table.th>
                <x-table.th>Loại</x-table.th>
                <x-table.th>Giá trị</x-table.th>
                <x-table.th>Giới hạn</x-table.th>
                <x-table.th>Thời gian</x-table.th>
                <x-table.th>Trạng thái</x-table.th>
                <x-table.th class="text-right">Hành động</x-table.th>
            </x-slot>

            <x-slot name="body">
                @forelse ($discounts as $discount)
                    <tr>
                        <x-table.td>{{ $discount->code }}</x-table.td>
                        <x-table.td>{{ ucfirst($discount->type) }}</x-table.td>
                        <x-table.td>
                            @if ($discount->type === 'percentage')
                                {{ $discount->value }}%
                            @elseif ($discount->type === 'fixed_amount')
                                {{ number_format($discount->value, 0, ',', '.') }} đ
                            @else
                                Miễn phí vận chuyển
                            @endif
                        </x-table.td>
                        <x-table.td>
                            {{ $discount->used }}/{{ $discount->usage_limit ?? '∞' }}
                        </x-table.td>
                        <x-table.td>
                            {{ $discount->start_date?->format('d/m/Y') }} - {{ $discount->end_date?->format('d/m/Y') }}
                        </x-table.td>
                        <x-table.td>
                            <x-badge :color="$discount->is_active ? 'green' : 'gray'">
                                {{ $discount->is_active ? 'Đang hoạt động' : 'Tạm ngưng' }}
                            </x-badge>
                        </x-table.td>
                        <x-table.td class="text-right space-x-2">
                            <x-button.link href="{{ route('admin.discounts.edit', $discount) }}">Sửa</x-button.link>
                            <x-button.delete
                                :action="route('admin.discounts.destroy', $discount)"
                                confirm="Bạn có chắc chắn muốn xóa mã {{ $discount->code }}?"
                            />
                        </x-table.td>
                    </tr>
                @empty
                    <x-table.empty colspan="7" />
                @endforelse
            </x-slot>
        </x-table>

        <div class="mt-4">
            {{ $discounts->links() }}
        </div>
    </div>
</x-admin-layout>
