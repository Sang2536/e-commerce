<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Notifications\ReviewApproved;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Hiển thị danh sách đánh giá
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user']);

        // Tìm kiếm
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Lọc theo trạng thái
        if ($request->has('approval_status') && $request->approval_status != 'all') {
            $isApproved = $request->approval_status === 'approved';
            $query->where('is_approved', $isApproved);
        }

        // Lọc theo xếp hạng
        if ($request->has('rating') && $request->rating != 'all') {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Hiển thị chi tiết đánh giá
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\View\View
     */
    public function show(Review $review)
    {
        $review->load(['product', 'user']);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Xóa đánh giá
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Đánh giá đã được xóa thành công.');
    }

    /**
     * Bật/tắt phê duyệt đánh giá
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleApproval(Review $review)
    {
        $oldStatus = $review->is_approved;
        $review->is_approved = !$oldStatus;
        $review->save();

        // Nếu đánh giá được phê duyệt, gửi thông báo cho người dùng
        if (!$oldStatus && $review->is_approved && $review->user) {
            $review->user->notify(new ReviewApproved($review));
        }

        $message = $review->is_approved
            ? 'Đánh giá đã được phê duyệt.'
            : 'Đánh giá đã bị từ chối.';

        return back()->with('success', $message);
    }
}
