<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán hàng loạt.
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total'
    ];

    /**
     * Các thuộc tính nên được ép kiểu.
     */
    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Lấy đơn hàng chứa mục này.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Lấy sản phẩm của mục đơn hàng.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Tính tổng tiền của mục đơn hàng.
     */
    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }
}
