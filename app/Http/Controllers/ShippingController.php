<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    /**
     * Hiển thị thông tin theo dõi vận chuyển
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function track(Order $order)
    {
        // Đảm bảo người dùng chỉ có thể xem đơn hàng của họ
        if (Auth::id() !== $order->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Lấy thông tin vận chuyển từ order
        $shippingInfo = json_decode($order->shipping_data, true) ?? [];

        // Nếu có mã vận đơn, lấy thông tin theo dõi từ API
        $trackingData = [];
        if (!empty($order->tracking_number)) {
            // Giả định sử dụng API của đơn vị vận chuyển
            // Trong thực tế, bạn sẽ tích hợp với API cụ thể của đơn vị vận chuyển
            try {
                // Mô phỏng gọi API theo dõi vận chuyển
                // $response = Http::get('https://api.shipping-provider.com/track', [
                //     'tracking_number' => $order->tracking_number,
                // ]);
                // $trackingData = $response->json();

                // Dữ liệu mẫu cho mục đích minh họa
                $trackingData = [
                    'status' => 'in_transit', // 'pending', 'in_transit', 'delivered', 'failed'
                    'estimated_delivery' => now()->addDays(3)->format('Y-m-d'),
                    'tracking_events' => [
                        [
                            'timestamp' => now()->subDays(1)->format('Y-m-d H:i:s'),
                            'status' => 'Đơn hàng đã được giao cho đơn vị vận chuyển',
                            'location' => 'Kho hàng TP. Hồ Chí Minh'
                        ],
                        [
                            'timestamp' => now()->subHours(12)->format('Y-m-d H:i:s'),
                            'status' => 'Đơn hàng đang được vận chuyển',
                            'location' => 'Trung tâm phân loại TP. Hồ Chí Minh'
                        ],
                        [
                            'timestamp' => now()->subHours(2)->format('Y-m-d H:i:s'),
                            'status' => 'Đơn hàng đang được giao đến địa chỉ nhận',
                            'location' => 'Chi nhánh giao hàng Quận 1'
                        ]
                    ]
                ];
            } catch (\Exception $e) {
                $trackingData = [
                    'error' => 'Không thể lấy thông tin vận chuyển. Vui lòng thử lại sau.'
                ];
            }
        }

        return view('shipping.track', compact('order', 'shippingInfo', 'trackingData'));
    }
}
