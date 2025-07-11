<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity'
    ];

    /**
     * Lấy thông tin người dùng sở hữu mục giỏ hàng
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Lấy thông tin sản phẩm của mục giỏ hàng
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
