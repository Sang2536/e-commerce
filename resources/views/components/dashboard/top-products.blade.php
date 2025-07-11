@props(['products'])

<ul class="list-group">
    @foreach ($products as $product)
        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
            <div class="d-flex align-items-center">
                @if ($product->thumbnail)
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" class="avatar avatar-sm me-3">
                @else
                    <div class="avatar avatar-sm me-3 bg-gradient-secondary">{{ substr($product->name, 0, 1) }}</div>
                @endif
                <div class="d-flex flex-column">
                    <h6 class="mb-1 text-dark text-sm">{{ $product->name }}</h6>
                    <span class="text-xs">{{ $product->sold_count }} đã bán</span>
                </div>
            </div>
            <div class="d-flex align-items-center text-sm font-weight-bold">
                {{ number_format($product->price, 0, ',', '.') }}đ
            </div>
        </li>
    @endforeach
</ul>
