<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    /**
     * Hiển thị danh sách phương thức thanh toán
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('name')->paginate(15);
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Hiển thị form tạo phương thức thanh toán mới
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.payment-methods.create');
    }

    /**
     * Lưu phương thức thanh toán mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|max:2048',
            'min_order_total' => 'nullable|numeric|min:0',
            'max_order_total' => 'nullable|numeric|min:0|gte:min_order_total',
            'extra_fee' => 'nullable|numeric|min:0',
            'fee_type' => 'nullable|in:fixed,percentage',
        ]);

        // Xử lý logo
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('payment-methods', 'public');
        }

        PaymentMethod::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'description' => $validated['description'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'is_active' => $request->has('is_active'),
            'logo' => $logoPath,
            'min_order_total' => $validated['min_order_total'] ?? null,
            'max_order_total' => $validated['max_order_total'] ?? null,
            'extra_fee' => $validated['extra_fee'] ?? 0,
            'fee_type' => $validated['fee_type'] ?? 'fixed',
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Phương thức thanh toán đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết phương thức thanh toán
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\View\View
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.show', compact('paymentMethod'));
    }

    /**
     * Hiển thị form chỉnh sửa phương thức thanh toán
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\View\View
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Cập nhật phương thức thanh toán
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods,code,' . $paymentMethod->id,
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|max:2048',
            'min_order_total' => 'nullable|numeric|min:0',
            'max_order_total' => 'nullable|numeric|min:0|gte:min_order_total',
            'extra_fee' => 'nullable|numeric|min:0',
            'fee_type' => 'nullable|in:fixed,percentage',
        ]);

        // Xử lý logo
        if ($request->hasFile('logo')) {
            // Xóa logo cũ
            if ($paymentMethod->logo) {
                Storage::disk('public')->delete($paymentMethod->logo);
            }

            $logoPath = $request->file('logo')->store('payment-methods', 'public');
            $paymentMethod->logo = $logoPath;
        }

        $paymentMethod->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'description' => $validated['description'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'is_active' => $request->has('is_active'),
            'logo' => $paymentMethod->logo,
            'min_order_total' => $validated['min_order_total'] ?? null,
            'max_order_total' => $validated['max_order_total'] ?? null,
            'extra_fee' => $validated['extra_fee'] ?? 0,
            'fee_type' => $validated['fee_type'] ?? 'fixed',
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Phương thức thanh toán đã được cập nhật thành công.');
    }

    /**
     * Xóa phương thức thanh toán
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Kiểm tra xem phương thức thanh toán có đang được sử dụng không
        $ordersUsingMethod = $paymentMethod->orders()->count();

        if ($ordersUsingMethod > 0) {
            return back()->with('error', 'Không thể xóa phương thức thanh toán đang được sử dụng trong các đơn hàng.');
        }

        // Xóa logo
        if ($paymentMethod->logo) {
            Storage::disk('public')->delete($paymentMethod->logo);
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Phương thức thanh toán đã được xóa thành công.');
    }
}
