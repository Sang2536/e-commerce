<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row -mx-4">
            <div class="md:flex-1 px-4">
                <div class="h-64 md:h-80 rounded-lg bg-gray-100 mb-4">
                    <img class="h-full w-full object-cover rounded-lg" src="{{ $product->image }}" alt="{{ $product->name }}">
                </div>
                <div class="flex -mx-2 mb-4">
                    <div class="w-1/2 px-2">
                        <button class="w-full bg-gray-900 text-white py-2 px-4 rounded-full font-bold hover:bg-gray-800">Add to Cart</button>
                    </div>
                    <div class="w-1/2 px-2">
                        <button class="w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-full font-bold hover:bg-gray-300">Add to Wishlist</button>
                    </div>
                </div>
            </div>
            <div class="md:flex-1 px-4">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $product->name }}</h2>
                <p class="text-gray-600 text-sm mb-4">
                    Category: <a href="{{ route('categories.show', $product->category) }}" class="text-indigo-600 hover:text-indigo-500">{{ $product->category->name }}</a>
                </p>
                <div class="flex items-center mb-4">
                    <div class="flex mr-2">
                        @php $avgRating = $product->average_rating @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $avgRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-gray-600 text-sm">
                        {{ number_format($avgRating, 1) }} sao
                        <a href="{{ route('products.reviews', $product) }}" class="text-indigo-600 hover:text-indigo-500 ml-1">
                            ({{ $product->reviews_count }} đánh giá)
                        </a>
                    </span>
                </div>
                <div class="flex mb-4">
                    <div class="mr-4">
                        <span class="font-bold text-gray-700">Price:</span>
                        <span class="text-gray-600">${{ $product->price }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-gray-700">Availability:</span>
                        <span class="text-gray-600">{{ $product->in_stock ? 'In Stock' : 'Out of Stock' }}</span>
                    </div>
                </div>
                <div class="mb-4">
                    <span class="font-bold text-gray-700">Description:</span>
                    <p class="text-gray-600 text-sm mt-2">
                        {{ $product->description }}
                    </p>
                </div>
                <div>
                    <span class="font-bold text-gray-700">Product Highlights:</span>
                    <ul class="list-disc list-inside text-gray-600 text-sm mt-2">
                        <li>Lorem ipsum dolor sit amet</li>
                        <li>Consectetur adipiscing elit</li>
                        <li>Facilisis in pretium nisl aliquet</li>
                        <li>Nulla volutpat aliquam velit</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <h3 class="text-gray-800 text-xl font-medium mb-4">Đánh giá gần đây</h3>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                @forelse ($product->reviews()->with('user')->latest()->take(3)->get() as $review)
                    <div class="border-b border-gray-200 p-6 hover:bg-gray-50 transition duration-150 ease-in-out">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="flex items-center">
                                    <div class="flex">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-600 ml-2">{{ $review->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="mt-3 text-gray-700">
                                    {{ $review->comment }}
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $review->user->name }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        Chưa có đánh giá nào cho sản phẩm này.
                    </div>
                @endforelse

                <div class="p-4 bg-gray-50 border-t border-gray-200 text-center">
                    <a href="{{ route('products.reviews', $product) }}" class="inline-block text-indigo-600 hover:text-indigo-800">
                        Xem tất cả đánh giá hoặc thêm đánh giá mới
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-16">
            <h3 class="text-gray-600 text-2xl font-medium">Related Products</h3>
            <!-- Related Products Grid -->
        </div>
    </div>
</x-app-layout>
