<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Hiển thị danh sách đánh giá mới nhất
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $reviews = Review::with(['product', 'user'])
            ->latest()
            ->paginate(10);

        return view('reviews.index', compact('reviews'));
    }

    /**
     * Hiển thị danh sách đánh giá cho sản phẩm cụ thể
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\View\View
     */
    public function productReviews(Product $product)
    {
        $reviews = $product->reviews()
            ->with('user')
            ->latest()
            ->paginate(5);

        return view('reviews.product', compact('product', 'reviews'));
    }

    /**
     * Lưu đánh giá mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ]);

        $review = new Review([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'user_id' => Auth::id(),
        ]);

        $product->reviews()->save($review);

        return redirect()->route('products.reviews', $product)
            ->with('success', 'Đánh giá của bạn đã được gửi thành công!');
    }

    /**
     * Cập nhật đánh giá
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Review $review)
    {
        // Kiểm tra quyền chỉnh sửa
        $this->authorize('update', $review);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()
            ->with('success', 'Đánh giá của bạn đã được cập nhật thành công!');
    }

    /**
     * Xóa đánh giá
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Review $review)
    {
        // Kiểm tra quyền xóa
        $this->authorize('delete', $review);

        $review->delete();

        return redirect()->back()
            ->with('success', 'Đánh giá đã được xóa thành công!');
    }
}
