@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-8">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Đặt hàng thành công!</h1>
            <p class="text-gray-600 mt-2">Cảm ơn bạn đã mua sắm tại cửa hàng chúng tôi.</p>
        </div>

        @if(session('order_id'))
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <p class="text-gray-700 font-medium">Mã đơn hàng: <span class="font-bold text-indigo-600">#{{ session('order_id') }}</span></p>
        </div>
        @endif

        @if(session('bank_transfer'))
        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <h3 class="font-semibold text-blue-800 mb-2">Thông tin chuyển khoản</h3>
            <p class="text-sm text-blue-700 mb-2">Vui lòng chuyển khoản theo thông tin sau:</p>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>Ngân hàng: <strong>Vietcombank</strong></li>
                <li>Số tài khoản: <strong>1234567890</strong></li>
                <li>Chủ tài khoản: <strong>CÔNG TY TNHH E-COMMERCE</strong></li>
                <li>Nội dung: <strong>Thanh toan don hang #{{ session('order_id') }}</strong></li>
            </ul>
            <p class="text-sm text-blue-700 mt-2">
                Đơn hàng sẽ được xử lý sau khi chúng tôi nhận được thanh toán của bạn.
            </p>
        </div>
        @endif

        <p class="text-gray-600 mb-6">
            Chúng tôi đã gửi email xác nhận đơn hàng đến địa chỉ email của bạn. Bạn có thể theo dõi trạng thái đơn hàng trong trang "Đơn hàng của tôi".
        </p>

        <div class="flex flex-col space-y-3">
            <a href="{{ route('orders.index') }}" class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Xem đơn hàng của tôi
            </a>
            <a href="{{ route('products.index') }}" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>
@endsection
