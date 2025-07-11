<div class="payment-methods mb-6">
    <h3 class="text-lg font-semibold mb-4">Phương thức thanh toán</h3>

    <div class="grid gap-4">
        <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
            <label for="payment_cod" class="flex items-center cursor-pointer">
                <input type="radio" id="payment_cod" name="payment_method" value="cod" checked
                    class="form-radio h-5 w-5 text-indigo-600">
                <span class="ml-2 flex-1">
                    <span class="font-medium block">Thanh toán khi nhận hàng (COD)</span>
                    <span class="text-sm text-gray-500">Thanh toán bằng tiền mặt khi nhận được đơn hàng</span>
                </span>
                <span class="shrink-0">
                    <img src="{{ asset('images/payment/cod.png') }}" alt="COD" class="h-8">
                </span>
            </label>
        </div>

        <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
            <label for="payment_vnpay" class="flex items-center cursor-pointer">
                <input type="radio" id="payment_vnpay" name="payment_method" value="vnpay"
                    class="form-radio h-5 w-5 text-indigo-600">
                <span class="ml-2 flex-1">
                    <span class="font-medium block">VNPay</span>
                    <span class="text-sm text-gray-500">Thanh toán an toàn với VNPay QR</span>
                </span>
                <span class="shrink-0">
                    <img src="{{ asset('images/payment/vnpay.png') }}" alt="VNPay" class="h-8">
                </span>
            </label>
        </div>

        <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
            <label for="payment_momo" class="flex items-center cursor-pointer">
                <input type="radio" id="payment_momo" name="payment_method" value="momo"
                    class="form-radio h-5 w-5 text-indigo-600">
                <span class="ml-2 flex-1">
                    <span class="font-medium block">MoMo</span>
                    <span class="text-sm text-gray-500">Thanh toán bằng ví điện tử MoMo</span>
                </span>
                <span class="shrink-0">
                    <img src="{{ asset('images/payment/momo.png') }}" alt="MoMo" class="h-8">
                </span>
            </label>
        </div>

        <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
            <label for="payment_bank" class="flex items-center cursor-pointer">
                <input type="radio" id="payment_bank" name="payment_method" value="bank"
                    class="form-radio h-5 w-5 text-indigo-600">
                <span class="ml-2 flex-1">
                    <span class="font-medium block">Chuyển khoản ngân hàng</span>
                    <span class="text-sm text-gray-500">Thanh toán bằng chuyển khoản ngân hàng</span>
                </span>
                <span class="shrink-0">
                    <img src="{{ asset('images/payment/bank.png') }}" alt="Bank Transfer" class="h-8">
                </span>
            </label>
        </div>
    </div>

    <div class="mt-6 payment-instructions hidden" id="bank_instructions">
        <div class="bg-blue-50 p-4 rounded-lg">
            <h4 class="font-medium text-blue-800">Thông tin chuyển khoản</h4>
            <p class="mt-2 text-sm text-blue-700">
                Vui lòng chuyển khoản theo thông tin sau:
            </p>
            <ul class="mt-2 text-sm text-blue-700 space-y-1">
                <li>Ngân hàng: <strong>Vietcombank</strong></li>
                <li>Số tài khoản: <strong>1234567890</strong></li>
                <li>Chủ tài khoản: <strong>CÔNG TY TNHH E-COMMERCE</strong></li>
                <li>Nội dung: <strong>Thanh toan don hang #<span id="order_id_ref"></span></strong></li>
            </ul>
            <p class="mt-2 text-sm text-blue-700">
                Đơn hàng sẽ được xử lý sau khi chúng tôi nhận được thanh toán của bạn.
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bankRadio = document.getElementById('payment_bank');
        const bankInstructions = document.getElementById('bank_instructions');
        const orderIdRef = document.getElementById('order_id_ref');
        const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');

        // Hiển thị hướng dẫn chuyển khoản khi chọn phương thức thanh toán chuyển khoản
        paymentMethodInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.value === 'bank') {
                    bankInstructions.classList.remove('hidden');
                    // Cập nhật ID đơn hàng trong hướng dẫn chuyển khoản
                    if (orderIdRef) {
                        orderIdRef.textContent = '{{ $orderId ?? "___" }}';
                    }
                } else {
                    bankInstructions.classList.add('hidden');
                }
            });
        });

        // Cập nhật action form khi chọn phương thức thanh toán
        const checkoutForm = document.getElementById('checkout_form');
        if (checkoutForm) {
            paymentMethodInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const paymentMethod = this.value;
                    let formAction = '{{ route("checkout.process") }}';

                    // Lưu phương thức thanh toán vào session storage để sử dụng trong quá trình thanh toán
                    sessionStorage.setItem('selected_payment_method', paymentMethod);
                });
            });
        }
    });
</script>
