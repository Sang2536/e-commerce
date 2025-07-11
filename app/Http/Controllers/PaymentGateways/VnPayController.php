<?php

namespace App\Http\Controllers\PaymentGateways;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VnPayController extends Controller
{
    /**
     * Xử lý kết quả trả về từ VNPay
     *
     * @param  \Illuminate\Http\Request  $request
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
            return redirect()->route('checkout.failed')
                ->with('error', 'Dữ liệu không hợp lệ!');
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
                    json_decode($payment->metadata ?? '{}', true),
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
                    json_decode($payment->metadata ?? '{}', true),
                    ['response_data' => $request->all()]
                ))
            ]);

            return redirect()->route('checkout.failed')
                ->with('error', 'Thanh toán thất bại với mã lỗi: ' . $vnp_ResponseCode);
        }
    }
}
