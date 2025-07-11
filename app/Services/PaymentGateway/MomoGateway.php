<?php

namespace App\Services\PaymentGateway;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MomoGateway implements PaymentGatewayInterface
{
    /**
     * Xử lý thanh toán qua MoMo
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment(Order $order)
    {
        try {
            $endpoint = config('payment.momo.endpoint');
            $partnerCode = config('payment.momo.partner_code');
            $accessKey = config('payment.momo.access_key');
            $secretKey = config('payment.momo.secret_key');
            $requestId = (string) Str::uuid();
            $orderId = 'MM' . time() . $order->id;
            $amount = (int) $order->total_amount;
            $orderInfo = 'Thanh toán đơn hàng #' . $order->id;
            $returnUrl = route('payment.callback') . '?order_id=' . $order->id;
            $notifyUrl = route('payment.webhook');
            $extraData = '';

            // Tạo chữ ký
            $rawSignature = "partnerCode={$partnerCode}&accessKey={$accessKey}&requestId={$requestId}&amount={$amount}&orderId={$orderId}&orderInfo={$orderInfo}&returnUrl={$returnUrl}&notifyUrl={$notifyUrl}&extraData={$extraData}";
            $signature = hash_hmac('sha256', $rawSignature, $secretKey);

            // Chuẩn bị dữ liệu gửi đi
            $requestData = [
                'partnerCode' => $partnerCode,
                'accessKey' => $accessKey,
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'returnUrl' => $returnUrl,
                'notifyUrl' => $notifyUrl,
                'extraData' => $extraData,
                'requestType' => 'captureMoMoWallet',
                'signature' => $signature,
            ];

            // Gửi yêu cầu đến MoMo
            $response = Http::post($endpoint, $requestData);
            $responseData = $response->json();

            if ($response->successful() && isset($responseData['payUrl'])) {
                // Lưu thông tin thanh toán
                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $order->total_amount,
                    'payment_method' => 'momo',
                    'transaction_id' => $orderId,
                    'status' => 'pending',
                    'payment_data' => $responseData,
                ]);

                // Chuyển hướng người dùng đến trang thanh toán của MoMo
                return redirect($responseData['payUrl']);
            } else {
                Log::error('Lỗi khi tạo thanh toán MoMo', [
                    'order_id' => $order->id,
                    'response' => $responseData
                ]);

                return redirect()->route('checkout.failed', ['order_id' => $order->id])
                               ->with('error', 'Không thể kết nối đến cổng thanh toán MoMo. Vui lòng thử lại sau.');
            }
        } catch (\Exception $e) {
            Log::error('Lỗi xử lý thanh toán MoMo', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('checkout.failed', ['order_id' => $order->id])
                           ->with('error', 'Đã xảy ra lỗi khi xử lý thanh toán: ' . $e->getMessage());
        }
    }

    /**
     * Xác minh thanh toán từ MoMo
     *
     * @param  array  $data
     * @return bool
     */
    public function verifyPayment(array $data)
    {
        try {
            if (!isset($data['signature'], $data['requestId'], $data['orderId'], $data['resultCode'])) {
                return false;
            }

            $secretKey = config('payment.momo.secret_key');
            $accessKey = config('payment.momo.access_key');
            $partnerCode = config('payment.momo.partner_code');

            // Tạo chữ ký để xác minh
            $rawSignature = "accessKey={$accessKey}&amount={$data['amount']}&extraData={$data['extraData']}&message={$data['message']}&orderId={$data['orderId']}&orderInfo={$data['orderInfo']}&orderType={$data['orderType']}&partnerCode={$partnerCode}&payType={$data['payType']}&requestId={$data['requestId']}&responseTime={$data['responseTime']}&resultCode={$data['resultCode']}";
            $signature = hash_hmac('sha256', $rawSignature, $secretKey);

            // Kiểm tra chữ ký và mã kết quả
            if ($signature === $data['signature'] && $data['resultCode'] === '0') {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Lỗi xác minh thanh toán MoMo', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return false;
        }
    }

    /**
     * Hoàn tiền cho đơn hàng qua MoMo
     *
     * @param  \App\Models\Order  $order
     * @param  float|null  $amount
     * @return bool
     */
    public function refundPayment(Order $order, ?float $amount = null)
    {
        try {
            $payment = $order->payment;

            if (!$payment || $payment->payment_method !== 'momo' || !$payment->isSuccessful()) {
                return false;
            }

            $refundAmount = $amount ?? $order->total_amount;
            $endpoint = config('payment.momo.refund_endpoint');
            $partnerCode = config('payment.momo.partner_code');
            $accessKey = config('payment.momo.access_key');
            $secretKey = config('payment.momo.secret_key');
            $requestId = (string) Str::uuid();
            $orderId = 'RF' . time() . $order->id;
            $transId = $payment->transaction_id;

            // Tạo chữ ký
            $rawSignature = "partnerCode={$partnerCode}&accessKey={$accessKey}&requestId={$requestId}&amount={$refundAmount}&orderId={$orderId}&transId={$transId}&description=Refund";
            $signature = hash_hmac('sha256', $rawSignature, $secretKey);

            // Chuẩn bị dữ liệu gửi đi
            $requestData = [
                'partnerCode' => $partnerCode,
                'accessKey' => $accessKey,
                'requestId' => $requestId,
                'amount' => (int) $refundAmount,
                'orderId' => $orderId,
                'transId' => $transId,
                'description' => 'Hoàn tiền đơn hàng #' . $order->id,
                'signature' => $signature,
            ];

            // Gửi yêu cầu đến MoMo
            $response = Http::post($endpoint, $requestData);
            $responseData = $response->json();

            if ($response->successful() && isset($responseData['resultCode']) && $responseData['resultCode'] === '0') {
                // Cập nhật trạng thái thanh toán
                $payment->update([
                    'status' => 'refunded',
                    'payment_data' => array_merge($payment->payment_data ?? [], ['refund' => $responseData])
                ]);

                return true;
            } else {
                Log::error('Lỗi khi hoàn tiền MoMo', [
                    'order_id' => $order->id,
                    'response' => $responseData
                ]);

                return false;
            }
        } catch (\Exception $e) {
            Log::error('Lỗi xử lý hoàn tiền MoMo', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}
