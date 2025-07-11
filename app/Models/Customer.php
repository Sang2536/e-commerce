<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'avatar',
        'email',
        'phone',
        'address',
        'city',
        'zip_code',
        'state',
        'rank',
        'group',
        'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Lấy tất cả các mục yêu thích của khách hàng.
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Lấy các đơn hàng của khách hàng.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Lấy các giao dịch của đơn hàng.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Lấy các đánh giá của khách hàng.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Thông tin giảm giá cho từng hạng khách hàng
     */
    public function discounts(): MorphToMany
    {
        return $this->morphToMany(Discount::class, 'discountable', 'discount_links');
    }
}
