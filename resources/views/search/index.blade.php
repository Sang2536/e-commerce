@extends('resources.views.layouts.app')

@section('title', 'Tìm kiếm: ' . $query)

@section('content')
    <div class="container mx-auto py-6 px-4">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Bộ lọc tìm kiếm -->
            <div class="w-full md:w-1/4 bg-white rounded-lg shadow-md p-4">
                <h2 class="text-lg font-semibold mb-4">Bộ lọc</h2>

                <form action="{{ route('search.index') }}" method="GET" id="filter-form">
                    <input type="hidden" name="q" value="{{ $query }}">

                    <!-- Danh mục -->
                    <div class="mb-4">
                        <h3 class="font-medium mb-2">Danh mục</h3>
                        @foreach(\App\Models\Category::all() as $category)
                            <div class="flex items-center mb-2">
                                <input type="radio" id="category-{{ $category->id }}" name="category"
                                       value="{{ $category->id }}"
                                       {{ $selectedCategory == $category->id ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <label for="category-{{ $category->id }}"
                                       class="ml-2 text-sm text-gray-700">{{ $category->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Giá -->
                    <div class="mb-4">
                        <h3 class="font-medium mb-2">Khoảng giá</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="min_price" class="block text-sm text-gray-600 mb-1">Từ</label>
                                <input type="number" id="min_price" name="min_price" value="{{ $minPrice }}"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="max_price" class="block text-sm text-gray-600 mb-1">Đến</label>
                                <input type="number" id="max_price" name="max_price" value="{{ $maxPrice }}"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Sắp xếp -->
                    <div class="mb-4">
                        <h3 class="font-medium mb-2">Sắp xếp theo</h3>
                        <select name="sort" id="sort"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="relevance" {{ $sort == 'relevance' ? 'selected' : '' }}>Liên quan nhất
                            </option>
                            <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Giá giảm dần
                            </option>
                            <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="rating" {{ $sort == 'rating' ? 'selected' : '' }}>Đánh giá cao</option>
                        </select>
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">
                        Áp dụng
                    </button>
                    <button type="button" id="reset-filter"
                            class="w-full mt-2 bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300 transition duration-300">
                        Đặt lại
                    </button>
                </form>
            </div>

            <!-- Kết quả tìm kiếm -->
            <div class="w-full md:w-3/4">
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <h1 class="text-xl font-semibold">Kết quả tìm kiếm: "{{ $query }}"</h1>
                            <p class="text-gray-600">Tìm thấy {{ $products->total() }} sản phẩm</p>
                        </div>
                        <div class="mt-2 md:mt-0">
                            <label for="mobile-sort" class="block text-sm text-gray-600 mb-1 md:hidden">Sắp xếp:</label>
                            <select id="mobile-sort"
                                    class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 md:hidden">
                                <option value="relevance" {{ $sort == 'relevance' ? 'selected' : '' }}>Liên quan nhất
                                </option>
                                <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Giá tăng dần
                                </option>
                                <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Giá giảm dần
                                </option>
                                <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="rating" {{ $sort == 'rating' ? 'selected' : '' }}>Đánh giá cao</option>
                            </select>
                        </div>
                    </div>
                </div>

                @if($products->isEmpty())
                    <div class="bg-white rounded-lg shadow-md p-8 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-700 mb-2">Không tìm thấy sản phẩm nào</h2>
                        <p class="text-gray-600 mb-4">Rất tiếc, chúng tôi không tìm thấy sản phẩm nào phù hợp với tìm
                            kiếm của bạn.</p>
                        <a href="{{ route('products.index') }}"
                           class="inline-block px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-300">Xem
                            tất cả sản phẩm</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div
                                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}"
                                         class="w-full h-48 object-cover">
                                </a>
                                <div class="p-4">
                                    <a href="{{ route('products.show', $product->slug) }}" class="block">
                                        <h2 class="text-lg font-semibold text-gray-800 hover:text-blue-600 mb-2">{{ $product->name }}</h2>
                                    </a>
                                    <div class="flex items-center mb-2">
                                        @php $rating = $product->reviews->avg('rating') ?? 0; @endphp
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $rating)
                                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor"
                                                         viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor"
                                                         viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span
                                            class="text-gray-500 text-sm ml-1">({{ $product->reviews->count() }})</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xl font-bold text-gray-800">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                        @if($product->old_price)
                                            <span class="text-sm text-gray-500 line-through">{{ number_format($product->old_price, 0, ',', '.') }}₫</span>
                                        @endif
                                    </div>
                                    <div class="mt-4 flex space-x-2">
                                        @auth
                                            <form action="{{ route('cart.add', $product->id) }}" method="POST"
                                                  class="flex-1">
                                                @csrf
                                                <button type="submit"
                                                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">
                                                    <i class="fas fa-shopping-cart mr-1"></i> Thêm vào giỏ
                                                </button>
                                            </form>
                                            <form action="{{ route('wishlist.add', $product->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="bg-gray-200 text-gray-600 py-2 px-3 rounded-md hover:bg-gray-300 transition duration-300">
                                                    <i class="far fa-heart"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}"
                                               class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300 text-center">
                                                <i class="fas fa-shopping-cart mr-1"></i> Thêm vào giỏ
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Đồng bộ bộ lọc mobile với desktop
            const mobileSort = document.getElementById('mobile-sort');
            const desktopSort = document.getElementById('sort');

            if (mobileSort && desktopSort) {
                mobileSort.addEventListener('change', function () {
                    desktopSort.value = this.value;
                    document.getElementById('filter-form').submit();
                });
            }

            // Nút reset bộ lọc
            const resetButton = document.getElementById('reset-filter');
            if (resetButton) {
                resetButton.addEventListener('click', function () {
                    const form = document.getElementById('filter-form');
                    const query = form.querySelector('input[name="q"]').value;
                    window.location.href = '{{ route("search.index") }}?q=' + encodeURIComponent(query);
                });
            }
        });
    </script>
@endpush
