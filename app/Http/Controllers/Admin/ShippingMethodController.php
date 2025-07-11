<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    /**
     * Hiển thị danh sách phương thức vận chuyển
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $shippingMethods = ShippingMethod::orderBy('name')->paginate(15);
        return view('admin.shipping-methods.index', compact('shippingMethods'));
    }

    /**
     * Hiển thị form tạo phương thức vận chuyển mới
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.shipping-methods.create');
    }

    /**
     * Lưu phương thức vận chuyển mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'min_days' => 'required|integer|min:0',
            'max_days' => 'required|integer|min:0|gte:min_days',
            'is_active' => 'boolean',
            'min_order_total' => 'nullable|numeric|min:0',
            'max_order_total' => 'nullable|numeric|min:0|gte:min_order_total',
            'applicable_countries' => 'nullable|string',
            'excluded_postal_codes' => 'nullable|string',
        ]);

        ShippingMethod::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'min_days' => $validated['min_days'],
            'max_days' => $validated['max_days'],
            'is_active' => $request->has('is_active'),
            'min_order_total' => $validated['min_order_total'] ?? null,
            'max_order_total' => $validated['max_order_total'] ?? null,
            'applicable_countries' => $validated['applicable_countries'] ?? null,
            'excluded_postal_codes' => $validated['excluded_postal_codes'] ?? null,
        ]);

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Phương thức vận chuyển đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết phương thức vận chuyển
     *
     * @param  \App\Models\ShippingMethod  $shippingMethod
     * @return \Illuminate\View\View
     */
    public function show(ShippingMethod $shippingMethod)
    {
        return view('admin.shipping-methods.show', compact('shippingMethod'));
    }

    /**
     * Hiển thị form chỉnh sửa phương thức vận chuyển
     *
     * @param  \App\Models\ShippingMethod  $shippingMethod
     * @return \Illuminate\View\View
     */
    public function edit(ShippingMethod $shippingMethod)
    {
        return view('admin.shipping-methods.edit', compact('shippingMethod'));
    }

    /**
     * Cập nhật phương thức vận chuyển
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShippingMethod  $shippingMethod
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'min_days' => 'required|integer|min:0',
            'max_days' => 'required|integer|min:0|gte:min_days',
            'is_active' => 'boolean',
            'min_order_total' => 'nullable|numeric|min:0',
            'max_order_total' => 'nullable|numeric|min:0|gte:min_order_total',
            'applicable_countries' => 'nullable|string',
            'excluded_postal_codes' => 'nullable|string',
        ]);

        $shippingMethod->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'min_days' => $validated['min_days'],
            'max_days' => $validated['max_days'],
            'is_active' => $request->has('is_active'),
            'min_order_total' => $validated['min_order_total'] ?? null,
            'max_order_total' => $validated['max_order_total'] ?? null,
            'applicable_countries' => $validated['applicable_countries'] ?? null,
            'excluded_postal_codes' => $validated['excluded_postal_codes'] ?? null,
        ]);

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Phương thức vận chuyển đã được cập nhật thành công.');
    }

    /**
     * Xóa phương thức vận chuyển
     *
     * @param  \App\Models\ShippingMethod  $shippingMethod
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ShippingMethod $shippingMethod)
    {
        // Kiểm tra xem phương thức vận chuyển có đang được sử dụng không
        $ordersUsingMethod = $shippingMethod->orders()->count();

        if ($ordersUsingMethod > 0) {
            return back()->with('error', 'Không thể xóa phương thức vận chuyển đang được sử dụng trong các đơn hàng.');
        }

        $shippingMethod->delete();

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Phương thức vận chuyển đã được xóa thành công.');
    }
}
