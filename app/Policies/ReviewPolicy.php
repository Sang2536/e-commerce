<?php
<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Xác định xem người dùng có thể cập nhật đánh giá hay không.
     */
    public function update(User $user, Review $review): bool
    {
        return $user->id === $review->user_id || $user->isAdmin();
    }

    /**
     * Xác định xem người dùng có thể xóa đánh giá hay không.
     */
    public function delete(User $user, Review $review): bool
    {
        return $user->id === $review->user_id || $user->isAdmin();
    }
}
namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Review $review)
    {
        // Người dùng có thể xóa đánh giá của chính họ
        return $user->id === $review->user_id || $user->isAdmin();
    }
}
