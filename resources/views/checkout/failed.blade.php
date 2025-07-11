@extends('layouts.app')

@section('title', 'Thanh toán thất bại')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Thanh toán thất bại</h1>
                <p class="text-gray-600 mt-2">Rất tiếc, đã xảy ra lỗi trong quá trình thanh toán.</p>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 rounded-lg">
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
            @endif

            <p class="text-gray-600 mb-6">
                Vui lòng thử lại hoặc chọn phương thức thanh toán khác. Nếu vấn đề vẫn tiếp tục, hãy liên hệ với chúng
                tôi để được hỗ trợ.
            </p>

            <div class="flex flex-col space-y-3">
                <a href="{{ route('checkout.index') }}"
                   class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Thử lại
                </a>
                <a href="{{ route('cart.index') }}"
                   class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Quay lại giỏ hàng
                </a>
                <a href="{{ route('contact.index') }}"
                   class="text-sm text-indigo-600 hover:text-indigo-500 text-center mt-2">
                    Liên hệ hỗ trợ
                </a>
            </div>
        </div>
    </div>
@endsection
