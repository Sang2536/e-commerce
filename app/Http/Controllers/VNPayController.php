<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{
    /**
     * Chuyển hướng đến cổng thanh toán VNPay
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

        $vnp_TmnCode = config('services.vnpay.tmn_code');
        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $vnp_Url = config('services.vnpay.payment_url');
        $vnp_ReturnUrl = route('payment.vnpay.return');

        $vnp_TxnRef = $order->id . '-' . time();
        $vnp_OrderInfo = 'Thanh toán đơn hàng #' . $order->id;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $order->total * 100; // VNPay yêu cầu số tiền * 100
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $request->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Lưu thông tin vào bảng payment
        Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total,
            'payment_method' => 'vnpay',
            'transaction_id' => $vnp_TxnRef,
            'status' => 'pending',
            'metadata' => json_encode([
                'vnp_TxnRef' => $vnp_TxnRef,
                'redirect_url' => $vnp_Url
            ])
        ]);

        return redirect($vnp_Url);
    }

    /**
     * Xử lý kết quả trả về từ VNPay
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function return(Request $request)
    {
        $vnp_HashSecret = config('services.vnpay.hash_secret');

        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $vnp_SecureHash = $request->vnp_SecureHash;

        // Kiểm tra chữ ký
        if ($secureHash !== $vnp_SecureHash) {
            return redirect()->route('checkout.failed')
                ->with('error', 'Chữ ký không hợp lệ!');
        }

        // Lấy thông tin giao dịch
        $vnp_ResponseCode = $request->vnp_ResponseCode;
        $vnp_TxnRef = $request->vnp_TxnRef;

        // Tách order_id từ vnp_TxnRef
        $order_id = explode('-', $vnp_TxnRef)[0];
        $order = Order::find($order_id);

        if (!$order) {
            return redirect()->route('checkout.failed')
                ->with('error', 'Không tìm thấy đơn hàng!');
        }

        // Cập nhật thông tin thanh toán
        $payment = Payment::where('transaction_id', $vnp_TxnRef)->first();

        if (!$payment) {
            return redirect()->route('checkout.failed')
                ->with('error', 'Không tìm thấy thông tin thanh toán!');
        }

        if ($vnp_ResponseCode === '00') {
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
                ->with('error', 'Thanh toán thất bại với mã lỗi: ' . $vnp_ResponseCode);
        }
    }

    /**
     * Xử lý thông báo tự động từ VNPay (IPN)
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function ipn(Request $request)
    {
        $vnp_HashSecret = config('services.vnpay.hash_secret');

        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Kiểm tra chữ ký
        if ($secureHash !== $vnp_SecureHash) {
            return response()->json([
                'RspCode' => '97',
                'Message' => 'Invalid signature'
            ]);
        }

        $vnp_ResponseCode = $request->vnp_ResponseCode;
        $vnp_TxnRef = $request->vnp_TxnRef;

        // Tách order_id từ vnp_TxnRef
        $order_id = explode('-', $vnp_TxnRef)[0];
        $order = Order::find($order_id);

        if (!$order) {
            return response()->json([
                'RspCode' => '01',
                'Message' => 'Order not found'
            ]);
        }

        // Cập nhật thông tin thanh toán
        $payment = Payment::where('transaction_id', $vnp_TxnRef)->first();

        if (!$payment) {
            return response()->json([
                'RspCode' => '02',
                'Message' => 'Payment not found'
            ]);
        }

        // Kiểm tra xem trạng thái đã được xử lý chưa
        if ($payment->status !== 'pending') {
            return response()->json([
                'RspCode' => '00',
                'Message' => 'Payment already processed'
            ]);
        }

        if ($vnp_ResponseCode === '00') {
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
            Log::channel('payment')->info('VNPay payment successful', [
                'order_id' => $order->id,
                'transaction_id' => $vnp_TxnRef
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
            Log::channel('payment')->error('VNPay payment failed', [
                'order_id' => $order->id,
                'transaction_id' => $vnp_TxnRef,
                'response_code' => $vnp_ResponseCode
            ]);
        }

        return response()->json([
            'RspCode' => '00',
            'Message' => 'Confirmed'
        ]);
    }
}
