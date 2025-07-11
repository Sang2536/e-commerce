<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-semibold">Chi tiết đánh giá #{{ $review->id }}</h1>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.reviews.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Quay lại
                            </a>
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Xóa đánh giá
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h2 class="text-lg font-medium mb-2">Thông tin đánh giá</h2>
                                <div class="space-y-2">
                                    <div>
                                        <span class="font-medium">ID:</span> {{ $review->id }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Thời gian tạo:</span> {{ $review->created_at->format('d/m/Y H:i:s') }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Đánh giá:</span>
                                        <div class="flex items-center mt-1">
                                            <span class="mr-2 font-bold">{{ $review->rating }}/5</span>
                                            <div class="flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h2 class="text-lg font-medium mb-2">Thông tin người đánh giá</h2>
                                <div class="space-y-2">
                                    <div>
                                        <span class="font-medium">Tên:</span> {{ $review->user->name }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Email:</span> {{ $review->user->email }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Ngày đăng ký:</span> {{ $review->user->created_at->format('d/m/Y') }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.users.edit', $review->user) }}" class="inline-flex items-center text-sm text-blue-600 hover:underline">
                                            Xem thông tin người dùng
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chi tiết đánh giá') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <a href="{{ route('admin.reviews.index') }}" class="text-indigo-600 hover:text-indigo-900">
                            &larr; Quay lại danh sách đánh giá
                        </a>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Thông tin đánh giá</h3>
                                <div class="mt-4 space-y-2">
                                    <p><span class="font-medium">ID:</span> {{ $review->id }}</p>
                                    <p>
                                        <span class="font-medium">Trạng thái:</span>
                                        <span class="px-2 py-1 rounded-full text-xs {{ $review->is_approved ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $review->is_approved ? 'Đã duyệt' : 'Chưa duyệt' }}
                                        </span>
                                    </p>
                                    <p><span class="font-medium">Ngày tạo:</span> {{ $review->created_at->format('d/m/Y H:i:s') }}</p>
                                    <p><span class="font-medium">Cập nhật lần cuối:</span> {{ $review->updated_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-4">
                                <form action="{{ route('reviews.toggle-approval', $review) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                        {{ $review->is_approved ? 'Hủy duyệt' : 'Duyệt đánh giá' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                        Xóa đánh giá
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin sản phẩm</h3>
                            <p><span class="font-medium">ID:</span> {{ $review->product->id }}</p>
                            <p>
                                <span class="font-medium">Tên sản phẩm:</span>
                                <a href="{{ route('admin.products.edit', $review->product) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $review->product->name }}
                                </a>
                            </p>
                            <p><span class="font-medium">Giá:</span> ${{ $review->product->price }}</p>
                            <p><span class="font-medium">Danh mục:</span> {{ $review->product->category->name }}</p>
                            <div class="mt-4">
                                <a href="{{ route('products.show', $review->product) }}" class="text-indigo-600 hover:text-indigo-900" target="_blank">
                                    Xem sản phẩm trên trang chính
                                </a>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin người dùng</h3>
                            <p><span class="font-medium">ID:</span> {{ $review->user->id }}</p>
                            <p><span class="font-medium">Tên:</span> {{ $review->user->name }}</p>
                            <p><span class="font-medium">Email:</span> {{ $review->user->email }}</p>
                            <p><span class="font-medium">Vai trò:</span> {{ ucfirst($review->user->role) }}</p>
                            <div class="mt-4">
                                <a href="{{ route('admin.users.edit', $review->user) }}" class="text-indigo-600 hover:text-indigo-900">
                                    Xem thông tin người dùng
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Nội dung đánh giá</h3>
                        <div class="mb-4">
                            <span class="font-medium">Đánh giá:</span>
                            <div class="flex mt-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-6 h-6 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                                <span class="ml-2 text-gray-600">{{ $review->rating }}/5</span>
                            </div>
                        </div>
                        <div>
                            <span class="font-medium">Bình luận:</span>
                            <div class="mt-2 p-4 bg-gray-50 rounded-md text-gray-700">
                                {{ $review->comment }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <h2 class="text-lg font-medium mb-4">Thông tin sản phẩm</h2>
                        <div class="flex flex-col md:flex-row md:space-x-6">
                            <div class="md:w-1/4 mb-4 md:mb-0">
                                <img src="{{ $review->product->image_url ?? '/img/product-placeholder.png' }}" alt="{{ $review->product->name }}" class="w-full h-auto rounded-lg">
                            </div>
                            <div class="md:w-3/4">
                                <h3 class="text-xl font-semibold">{{ $review->product->name }}</h3>
                                <p class="text-gray-600 mt-2">{{ Str::limit($review->product->description, 200) }}</p>
                                <div class="mt-4">
                                    <span class="font-medium">Giá:</span> {{ number_format($review->product->price, 0, ',', '.') }} đ
                                </div>
                                <div class="mt-2">
                                    <span class="font-medium">Danh mục:</span> {{ $review->product->category->name ?? 'N/A' }}
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('admin.products.edit', $review->product) }}" class="inline-flex items-center text-blue-600 hover:underline">
                                        Quản lý sản phẩm
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-lg font-medium mb-4">Nội dung đánh giá</h2>
                        <div class="bg-white p-4 rounded border border-gray-200">
                            <p class="whitespace-pre-line">{{ $review->comment }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
