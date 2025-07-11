<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-6">Đánh giá sản phẩm gần đây</h1>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            @forelse ($reviews as $review)
                <div class="border-b border-gray-200 p-6 hover:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 hover:text-indigo-600">
                                <a href="{{ route('products.show', $review->product) }}">
                                    {{ $review->product->name }}
                                </a>
                            </h3>
                            <div class="flex items-center mt-1">
                                <div class="flex">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600 ml-2">{{ $review->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="mt-3 text-sm text-gray-700">
                                {{ $review->comment }}
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            Bởi: {{ $review->user->name }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    Chưa có đánh giá nào.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    </div>
</x-app-layout>
