<?php

namespace App\Services\PaymentGateway;

use App\Models\Order;

interface PaymentGatewayInterface
{
    /**
     * Xử lý thanh toán cho đơn hàng
     *
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function processPayment(Order $order);

    /**
     * Xác minh thanh toán
     *
     * @param  array  $data
     * @return bool
     */
    public function verifyPayment(array $data);

    /**
     * Hoàn tiền cho đơn hàng
     *
     * @param  \App\Models\Order  $order
     * @param  float|null  $amount
     * @return bool
     */
    public function refundPayment(Order $order, ?float $amount = null);
}
