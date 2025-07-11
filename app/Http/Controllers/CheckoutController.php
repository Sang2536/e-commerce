<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Hiển thị trang thanh toán
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                           ->with('error', 'Giỏ hàng của bạn đang trống, hãy thêm sản phẩm trước khi thanh toán.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $tax = $subtotal * 0.1; // 10% thuế
        $shipping = 30000; // Phí vận chuyển cố định
        $total = $subtotal + $tax + $shipping;

        $addresses = $user->addresses;
        $paymentMethods = [
            'cod' => 'Thanh toán khi nhận hàng',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'credit_card' => 'Thẻ tín dụng/ghi nợ',
            'momo' => 'Ví MoMo',
            'zalopay' => 'ZaloPay'
        ];

        return view('checkout.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
            'addresses' => $addresses,
            'paymentMethods' => $paymentMethods
        ]);
    }

    /**
     * Xử lý đơn hàng và thanh toán
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cod,bank_transfer,credit_card,momo,zalopay',
            'notes' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                           ->with('error', 'Giỏ hàng của bạn đang trống, hãy thêm sản phẩm trước khi thanh toán.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $tax = $subtotal * 0.1; // 10% thuế
        $shipping = 30000; // Phí vận chuyển cố định
        $total = $subtotal + $tax + $shipping;

        $addressId = $request->input('address_id');
        $paymentMethod = $request->input('payment_method');
        $notes = $request->input('notes');

        try {
            DB::beginTransaction();

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $addressId,
                'payment_method' => $paymentMethod,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_fee' => $shipping,
                'total_amount' => $total,
                'notes' => $notes,
                'status' => 'pending',
                'payment_status' => $paymentMethod === 'cod' ? 'pending' : 'processing',
                'tracking_number' => 'TRK' . strtoupper(uniqid()),
            ]);

            // Tạo các mục đơn hàng
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'total' => $item->product->price * $item->quantity,
                ]);
            }

            // Xóa giỏ hàng
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            // Xử lý phương thức thanh toán
            if ($paymentMethod === 'cod') {
                // Thanh toán khi nhận hàng, chuyển hướng đến trang thành công
                return redirect()->route('checkout.success', ['order_id' => $order->id])
                               ->with('success', 'Đơn hàng của bạn đã được đặt thành công!');
            } else {
                // Thanh toán trực tuyến, chuyển hướng đến cổng thanh toán
                $paymentGateway = $this->getPaymentGateway($paymentMethod);

                return $paymentGateway->processPayment($order);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('checkout.index')
                           ->with('error', 'Đã xảy ra lỗi khi xử lý đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị trang thanh toán thành công
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function success(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::with(['items.product', 'address', 'user'])->findOrFail($orderId);

        // Kiểm tra xem đơn hàng có thuộc về người dùng hiện tại không
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này');
        }

        return view('checkout.success', ['order' => $order]);
    }

    /**
     * Hiển thị trang thanh toán thất bại
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function failed(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::findOrFail($orderId);

        // Kiểm tra xem đơn hàng có thuộc về người dùng hiện tại không
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này');
        }

        return view('checkout.failed', ['order' => $order]);
    }

    /**
     * Lấy cổng thanh toán dựa trên phương thức thanh toán
     *
     * @param  string  $paymentMethod
     * @return \App\Services\PaymentGateway\PaymentGatewayInterface
     */
    private function getPaymentGateway($paymentMethod)
    {
        switch ($paymentMethod) {
            case 'credit_card':
                return app(\App\Services\PaymentGateway\CreditCardGateway::class);
            case 'momo':
                return app(\App\Services\PaymentGateway\MomoGateway::class);
            case 'zalopay':
                return app(\App\Services\PaymentGateway\ZaloPayGateway::class);
            case 'bank_transfer':
                return app(\App\Services\PaymentGateway\BankTransferGateway::class);
            default:
                throw new \InvalidArgumentException('Phương thức thanh toán không hợp lệ');
        }
    }
}
