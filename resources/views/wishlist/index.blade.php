@extends('resources.views.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Danh Sách Yêu Thích</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                 role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($wishlistItems->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($wishlistItems as $item)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">Không có hình ảnh</span>
                            </div>
                        @endif

                        <div class="p-4">
                            <h2 class="text-xl font-semibold mb-2">{{ $item->product->name }}</h2>
                            <p class="text-gray-600 mb-2 line-clamp-2">{{ $item->product->description }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-red-600">{{ number_format($item->product->price, 0, ',', '.') }} VNĐ</span>
                                <div class="flex space-x-2">
                                    <form action="{{ route('cart.add', $item->product) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                            Thêm vào giỏ
                                        </button>
                                    </form>

                                    <form action="{{ route('wishlist.remove', $item->product) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-100 p-6 rounded-lg text-center">
                <p class="text-lg text-gray-600">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
                <a href="{{ route('products.index') }}"
                   class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Xem sản phẩm
                </a>
            </div>
        @endif
    </div>
@endsection
