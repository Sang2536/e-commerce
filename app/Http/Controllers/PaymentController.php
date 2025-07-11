<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Xử lý callback từ cổng thanh toán
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
        $paymentId = $request->input('payment_id');
        $status = $request->input('status');
        $orderId = $request->input('order_id');

        Log::info('Payment callback received', [
            'payment_id' => $paymentId,
            'status' => $status,
            'order_id' => $orderId,
        ]);

        $order = Order::findOrFail($orderId);

        if ($status === 'success') {
            // Cập nhật trạng thái đơn hàng thành công
            $order->update([
                'status' => 'processing',
                'payment_status' => 'paid'
            ]);

            // Tạo bản ghi thanh toán
            Payment::create([
                'order_id' => $orderId,
                'amount' => $order->total_amount,
                'payment_method' => $request->input('payment_method', 'online'),
                'transaction_id' => $paymentId,
                'status' => 'completed',
            ]);

            return redirect()->route('checkout.success', ['order_id' => $orderId])
                           ->with('success', 'Thanh toán thành công! Đơn hàng của bạn đang được xử lý.');
        } else {
            // Cập nhật trạng thái đơn hàng thất bại
            $order->update([
                'status' => 'pending',
                'payment_status' => 'failed'
            ]);

            // Tạo bản ghi thanh toán thất bại
            Payment::create([
                'order_id' => $orderId,
                'amount' => $order->total_amount,
                'payment_method' => $request->input('payment_method', 'online'),
                'transaction_id' => $paymentId,
                'status' => 'failed',
            ]);

            return redirect()->route('checkout.failed', ['order_id' => $orderId])
                           ->with('error', 'Thanh toán thất bại. Vui lòng thử lại hoặc chọn phương thức thanh toán khác.');
        }
    }

    /**
     * Xử lý webhook từ cổng thanh toán
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();

        Log::info('Payment webhook received', $payload);

        // Xác thực webhook
        if (!$this->verifyWebhookSignature($request)) {
            Log::warning('Invalid webhook signature');
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $event = $payload['event'] ?? null;
        $data = $payload['data'] ?? [];

        if ($event === 'payment.success') {
            $orderId = $data['order_id'] ?? null;

            if ($orderId) {
                $order = Order::find($orderId);

                if ($order) {
                    $order->update([
                        'status' => 'processing',
                        'payment_status' => 'paid'
                    ]);

                    // Cập nhật hoặc tạo bản ghi thanh toán
                    Payment::updateOrCreate(
                        ['order_id' => $orderId],
                        [
                            'amount' => $data['amount'] ?? $order->total_amount,
                            'payment_method' => $data['payment_method'] ?? 'online',
                            'transaction_id' => $data['transaction_id'] ?? null,
                            'status' => 'completed',
                        ]
                    );
                }
            }
        } elseif ($event === 'payment.failed') {
            $orderId = $data['order_id'] ?? null;

            if ($orderId) {
                $order = Order::find($orderId);

                if ($order) {
                    $order->update([
                        'status' => 'pending',
                        'payment_status' => 'failed'
                    ]);

                    // Cập nhật hoặc tạo bản ghi thanh toán
                    Payment::updateOrCreate(
                        ['order_id' => $orderId],
                        [
                            'amount' => $data['amount'] ?? $order->total_amount,
                            'payment_method' => $data['payment_method'] ?? 'online',
                            'transaction_id' => $data['transaction_id'] ?? null,
                            'status' => 'failed',
                        ]
                    );
                }
            }
        }

        return response()->json(['message' => 'Webhook processed successfully']);
    }

    /**
     * Xác thực chữ ký webhook
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    private function verifyWebhookSignature(Request $request)
    {
        // Lấy chữ ký từ header
        $signature = $request->header('X-Payment-Signature');

        if (!$signature) {
            return false;
        }

        // Lấy secret key từ cấu hình
        $secretKey = config('payment.webhook_secret');

        // Tạo chữ ký từ payload
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $secretKey);

        // So sánh chữ ký
        return hash_equals($expectedSignature, $signature);
    }
}
