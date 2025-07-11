<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    /**
     * lấy thông tin đơn hàng
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Lấy thông tin giao dịch
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
