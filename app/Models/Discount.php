<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'type', 'value', 'max_discount', 'min_order_value',
        'usage_limit', 'used', 'start_date', 'end_date', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Lấy thông tin sản phẩm được áp dụng mã giảm giá
     */
    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'discountable', 'discount_links');
    }

    /**
     * Lấy thông tin đơn hàng dã áp dụng mã giảm giá
     */
    public function orders(): MorphToMany
    {
        return $this->morphedByMany(Order::class, 'discountable', 'discount_links')->withPivot('discount_amount');
    }

    /**
     * Lấy thông tin hạng khách hàng
     */
    public function customers(): MorphToMany
    {
        return $this->morphedByMany(Customer::class, 'discountable', 'discount_links');
    }
}
