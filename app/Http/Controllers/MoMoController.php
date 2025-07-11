<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoMoController extends Controller
{
    /**
     * Chuyển hướng đến cổng thanh toán MoMo
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        // Kiểm tra xem đơn hàng có thể thanh toán không
        if ($order->status !== 'pending') {
            return redirect()->route('checkout.failed')
                ->with('error', 'Đơn hàng này không thể thanh toán.');
        }

        $endpoint = config('services.momo.endpoint');
        $partnerCode = config('services.momo.partner_code');
        $accessKey = config('services.momo.access_key');
        $secretKey = config('services.momo.secret_key');
        $orderInfo = 'Thanh toán đơn hàng #' . $order->id;
        $amount = (string)$order->total;
        $orderId = $order->id . '-' . time();
        $redirectUrl = route('payment.momo.return');
        $ipnUrl = route('payment.momo.ipn');
        $extraData = '';

        $requestId = time() . "";
        $requestType = "captureWallet";
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => 'E-Commerce Shop',
            'storeId' => 'E-CommerceShop',
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        try {
            $response = Http::post($endpoint, $data);
            $result = $response->json();

            if ($response->successful() && isset($result['payUrl'])) {
                // Lưu thông tin vào bảng payment
                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $order->total,
                    'payment_method' => 'momo',
                    'transaction_id' => $orderId,
                    'status' => 'pending',
                    'metadata' => json_encode([
                        'request_id' => $requestId,
                        'order_id' => $orderId,
                        'pay_url' => $result['payUrl']
                    ])
                ]);

                return redirect($result['payUrl']);
            } else {
                Log::channel('payment')->error('MoMo payment error', [
                    'order_id' => $order->id,
                    'response' => $result
                ]);

                return redirect()->route('checkout.failed')
                    ->with('error', 'Không thể kết nối với cổng thanh toán MoMo.');
            }
        } catch (\Exception $e) {
            Log::channel('payment')->error('MoMo payment exception', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('checkout.failed')
                ->with('error', 'Đã xảy ra lỗi khi kết nối với cổng thanh toán MoMo.');
        }
    }

    /**
     * Xử lý kết quả trả về từ MoMo
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function return(Request $request)
    {
        $secretKey = config('services.momo.secret_key');

        if ($request->has('partnerCode') && $request->has('orderId') && $request->has('resultCode')) {
            // Xác minh chữ ký
            $rawHash = "accessKey=" . $request->accessKey . "&amount=" . $request->amount . "&extraData=" . $request->extraData . "&message=" . $request->message . "&orderId=" . $request->orderId . "&orderInfo=" . $request->orderInfo . "&orderType=" . $request->orderType . "&partnerCode=" . $request->partnerCode . "&payType=" . $request->payType . "&requestId=" . $request->requestId . "&responseTime=" . $request->responseTime . "&resultCode=" . $request->resultCode . "&transId=" . $request->transId;
            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            if ($signature !== $request->signature) {
                return redirect()->route('checkout.failed')
                    ->with('error', 'Chữ ký không hợp lệ!');
            }

            // Tách order_id từ orderId
            $order_id = explode('-', $request->orderId)[0];
            $order = Order::find($order_id);

            if (!$order) {
                return redirect()->route('checkout.failed')
                    ->with('error', 'Không tìm thấy đơn hàng!');
            }

            // Cập nhật thông tin thanh toán
            $payment = Payment::where('transaction_id', $request->orderId)->first();

            if (!$payment) {
                return redirect()->route('checkout.failed')
                    ->with('error', 'Không tìm thấy thông tin thanh toán!');
            }

            if ($request->resultCode === '0') {
                // Thanh toán thành công
                $payment->update([
                    'status' => 'completed',
                    'metadata' => json_encode(array_merge(
                        json_decode($payment->metadata, true),
                        ['response_data' => $request->all()]
                    ))
                ]);

                // Cập nhật trạng thái đơn hàng
                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'paid'
                ]);

                return redirect()->route('checkout.success')
                    ->with('success', 'Thanh toán thành công!');
            } else {
                // Thanh toán thất bại
                $payment->update([
                    'status' => 'failed',
                    'metadata' => json_encode(array_merge(
                        json_decode($payment->metadata, true),
                        ['response_data' => $request->all()]
                    ))
                ]);

                return redirect()->route('checkout.failed')
                    ->with('error', 'Thanh toán thất bại với mã lỗi: ' . $request->resultCode);
            }
        }

        return redirect()->route('checkout.failed')
            ->with('error', 'Dữ liệu không hợp lệ!');
    }

    /**
     * Xử lý thông báo tự động từ MoMo (IPN)
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function ipn(Request $request)
    {
        $secretKey = config('services.momo.secret_key');

        // Xác minh chữ ký
        $rawHash = "accessKey=" . $request->accessKey . "&amount=" . $request->amount . "&extraData=" . $request->extraData . "&message=" . $request->message . "&orderId=" . $request->orderId . "&orderInfo=" . $request->orderInfo . "&orderType=" . $request->orderType . "&partnerCode=" . $request->partnerCode . "&payType=" . $request->payType . "&requestId=" . $request->requestId . "&responseTime=" . $request->responseTime . "&resultCode=" . $request->resultCode . "&transId=" . $request->transId;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        if ($signature !== $request->signature) {
            return response()->json([
                'message' => 'Invalid signature',
                'status' => 'error'
            ], 400);
        }

        // Tách order_id từ orderId
        $order_id = explode('-', $request->orderId)[0];
        $order = Order::find($order_id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
                'status' => 'error'
            ], 404);
        }

        // Cập nhật thông tin thanh toán
        $payment = Payment::where('transaction_id', $request->orderId)->first();

        if (!$payment) {
            return response()->json([
                'message' => 'Payment not found',
                'status' => 'error'
            ], 404);
        }

        // Kiểm tra xem trạng thái đã được xử lý chưa
        if ($payment->status !== 'pending') {
            return response()->json([
                'message' => 'Payment already processed',
                'status' => 'success'
            ]);
        }

        if ($request->resultCode === '0') {
            // Thanh toán thành công
            $payment->update([
                'status' => 'completed',
                'metadata' => json_encode(array_merge(
                    json_decode($payment->metadata, true),
                    ['ipn_data' => $request->all()]
                ))
            ]);

            // Cập nhật trạng thái đơn hàng
            $order->update([
                'status' => 'processing',
                'payment_status' => 'paid'
            ]);

            // Ghi log
            Log::channel('payment')->info('MoMo payment successful', [
                'order_id' => $order->id,
                'transaction_id' => $request->orderId,
                'momo_transaction_id' => $request->transId
            ]);
        } else {
            // Thanh toán thất bại
            $payment->update([
                'status' => 'failed',
                'metadata' => json_encode(array_merge(
                    json_decode($payment->metadata, true),
                    ['ipn_data' => $request->all()]
                ))
            ]);

            // Ghi log
            Log::channel('payment')->error('MoMo payment failed', [
                'order_id' => $order->id,
                'transaction_id' => $request->orderId,
                'result_code' => $request->resultCode
            ]);
        }

        return response()->json([
            'message' => 'IPN processed successfully',
            'status' => 'success'
        ]);
    }
}
