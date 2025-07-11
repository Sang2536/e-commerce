<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipping extends Model
{
    protected $fillable = [
        'order_id',
        'shipping_method_id',
        'receiver_name',
        'receiver_phone',
        'address',
        'district',
        'province',
        'country',
        'shipping_fee',
        'tracking_code',
        'status',
    ];

    protected $casts = [
        'shipping_fee' => 'float',
    ];

    /**
     * Mỗi shipping thuộc về một đơn hàng
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mỗi shipping dùng một phương thức vận chuyển
     */
    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }
}
