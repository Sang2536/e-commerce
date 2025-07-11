<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:text-indigo-800">
                &larr; Quay lại {{ $product->name }}
            </a>
        </div>

        <h1 class="text-2xl font-semibold mb-2">Đánh giá cho {{ $product->name }}</h1>

        <div class="flex items-center mb-6">
            <div class="flex">
                @php $avgRating = $product->average_rating @endphp
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= $avgRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                @endfor
            </div>
            <span class="ml-2 text-gray-600">{{ number_format($avgRating, 1) }} sao từ {{ $product->reviews_count }} đánh giá</span>
        </div>

        @auth
            @php $userReview = $product->reviews()->where('user_id', auth()->id())->first() @endphp
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-lg font-medium mb-4">{{ $userReview ? 'Cập nhật đánh giá của bạn' : 'Thêm đánh giá của bạn' }}</h2>

                <form action="{{ $userReview ? route('reviews.update', $userReview) : route('reviews.store', $product) }}" method="POST">
                    @csrf
                    @if($userReview)
                        @method('PUT')
                    @endif

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Đánh giá</label>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="mr-2 cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" class="sr-only" {{ ($userReview && $userReview->rating == $i) ? 'checked' : '' }} required>
                                    <svg class="w-8 h-8 star-rating" data-rating="{{ $i }}" fill="{{ ($userReview && $userReview->rating >= $i) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="{{ ($userReview && $userReview->rating >= $i) ? 'text-yellow-400' : 'text-gray-300' }}">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </label>
                            @endfor
                        </div>
                        @error('rating')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="comment" class="block text-gray-700 mb-2">Bình luận</label>
                        <textarea id="comment" name="comment" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>{{ $userReview ? $userReview->comment : '' }}</textarea>
                        @error('comment')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between items-center">
                        <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            {{ $userReview ? 'Cập nhật đánh giá' : 'Gửi đánh giá' }}
                        </button>

                        @if($userReview)
                            <form action="{{ route('reviews.destroy', $userReview) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 focus:outline-none" onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?')">
                                    Xóa đánh giá
                                </button>
                            </form>
                        @endif
                    </div>
                </form>
            </div>
        @else
            <div class="bg-gray-100 rounded-lg p-4 mb-8">
                <p class="text-center">Vui lòng <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800">đăng nhập</a> để thêm đánh giá</p>
            </div>
        @endauth

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <h2 class="text-xl font-medium p-6 bg-gray-50 border-b">Tất cả đánh giá</h2>

            @forelse ($reviews as $review)
                <div class="border-b border-gray-200 p-6">
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
        </div>

        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    </div>

    @push('scripts')
    <script>
        // Xử lý hiệu ứng cho đánh giá sao
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.dataset.rating;
                    stars.forEach(s => {
                        if (s.dataset.rating <= rating) {
                            s.classList.add('text-yellow-400');
                            s.classList.remove('text-gray-300');
                            s.setAttribute('fill', 'currentColor');
                        } else {
                            s.classList.remove('text-yellow-400');
                            s.classList.add('text-gray-300');
                            s.setAttribute('fill', 'none');
                        }
                    });
                });

                star.addEventListener('mouseover', function() {
                    const rating = this.dataset.rating;
                    stars.forEach(s => {
                        if (s.dataset.rating <= rating) {
                            s.classList.add('text-yellow-400');
                            s.classList.remove('text-gray-300');
                        }
                    });
                });

                star.addEventListener('mouseout', function() {
                    const checkedInput = document.querySelector('input[name="rating"]:checked');
                    const rating = checkedInput ? checkedInput.value : 0;

                    stars.forEach(s => {
                        if (s.dataset.rating <= rating) {
                            s.classList.add('text-yellow-400');
                            s.classList.remove('text-gray-300');
                            s.setAttribute('fill', 'currentColor');
                        } else {
                            s.classList.remove('text-yellow-400');
                            s.classList.add('text-gray-300');
                            s.setAttribute('fill', 'none');
                        }
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
