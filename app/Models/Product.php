<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'price',
        'stock',
        'image',
        'description',
        'featured',
        'is_active'
    ];

    /**
     * Các thuộc tính nên cast sang các kiểu khác
     *
     * @var array<string, string>
     */
    protected $casts = [
        'featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'float',
        'stock' => 'integer',
    ];

    /**
     * Lấy bản ghi user tạo sản phẩm này.
     */
    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Lấy bản ghi category cho sản phẩm này.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Lấy tất cả các bản ghi wishlist cho sản phẩm này.
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Lấy tất cả các đánh giá cho sản phẩm này
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Lấy tất cả các item trong đơn hàng chứa sản phẩm này
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Sản phẩm được áp dụng mã giảm giá
     */
    public function discounts(): MorphToMany
    {
        return $this->morphToMany(Discount::class, 'discountable', 'discount_links');
    }
}
