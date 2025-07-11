<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'payment_status',
        'payment_method',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zip_code',
        'notes',
        'subtotal',
        'tax',
        'shipping_cost',
        'total',
    ];

    /**
     * Lấy thông tin khách hàng sở hữu đơn hàng
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Lấy thông tin giao dịch của đơn hàng
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Lấy thông tin giao hàng
     */
    public function shippings(): HasMany
    {
        return $this->hasMany(Shipping::class);
    }

    /**
     * Lấy thông tin giảm giá đã đã sử dụng
     */
    public function discounts(): MorphToMany
    {
        return $this->morphToMany(Discount::class, 'discountable', 'discount_links')->withPivot('discount_amount');
    }

    /**
     * Lấy các mục trong đơn hàng
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Kiểm tra xem đơn hàng có thể hủy hay không
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }
}
