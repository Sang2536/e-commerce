<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'status',
        'transaction_id',
        'metadata'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'json'
    ];

    /**
     * Get the order associated with the payment.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope a query to only include payments with a specific method.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $method
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMethod($query, $method): Builder
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope a query to only include payments with a specific status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Kiểm tra xem thanh toán có thất bại hay không
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Lấy tên phương thức thanh toán cho hiển thị
     *
     * @return string
     */
    public function getPaymentMethodName(): string
    {
        $methods = [
            'cod' => 'Thanh toán khi nhận hàng',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'credit_card' => 'Thẻ tín dụng/ghi nợ',
            'momo' => 'Ví MoMo',
            'zalopay' => 'ZaloPay',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Lấy tên trạng thái thanh toán cho hiển thị
     *
     * @return string
     */
    public function getStatusName(): string
    {
        $statuses = [
            'pending' => 'Đang xử lý',
            'completed' => 'Hoàn thành',
            'failed' => 'Thất bại',
            'refunded' => 'Đã hoàn tiền',
        ];

        return $statuses[$this->status] ?? $this->status;
    }
}
