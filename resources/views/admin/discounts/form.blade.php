@props(['discount' => null])

@php
    $isEdit = $discount !== null;
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.discounts.update', $discount) : route('admin.discounts.store') }}">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <x-form.card>
        <x-form.input name="code" label="Mã giảm giá" :value="$discount->code ?? old('code')" required />

        <x-form.select name="type" label="Loại" :value="$discount->type ?? old('type')" required>
            <option value="percentage">Phần trăm</option>
            <option value="fixed_amount">Số tiền</option>
            <option value="free_shipping">Miễn phí vận chuyển</option>
        </x-form.select>

        <x-form.input name="value" label="Giá trị" type="number" step="0.01" :value="$discount->value ?? old('value')" required />

        <x-form.input name="max_discount" label="Mức giảm tối đa" type="number" step="0.01" :value="$discount->max_discount ?? old('max_discount')" />

        <x-form.input name="min_order_value" label="Đơn hàng tối thiểu" type="number" step="0.01" :value="$discount->min_order_value ?? old('min_order_value')" />

        <x-form.input name="usage_limit" label="Giới hạn sử dụng" type="number" :value="$discount->usage_limit ?? old('usage_limit')" />

        <x-form.input name="start_date" label="Ngày bắt đầu" type="datetime-local"
                      :value="isset($discount->start_date) ? $discount->start_date->format('Y-m-d\TH:i') : old('start_date')" />

        <x-form.input name="end_date" label="Ngày kết thúc" type="datetime-local"
                      :value="isset($discount->end_date) ? $discount->end_date->format('Y-m-d\TH:i') : old('end_date')" />

        <x-form.checkbox name="is_active" label="Đang hoạt động" :checked="$discount->is_active ?? true" />

        <x-form.submit>
            {{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}
        </x-form.submit>
    </x-form.card>
</form>
