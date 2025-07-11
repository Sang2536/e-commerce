<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxRate;
use Illuminate\Http\Request;

class TaxRateController extends Controller
{
    /**
     * Hiển thị danh sách thuế suất
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $taxRates = TaxRate::orderBy('name')->paginate(15);
        return view('admin.tax-rates.index', compact('taxRates'));
    }

    /**
     * Hiển thị form tạo thuế suất mới
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.tax-rates.create');
    }

    /**
     * Lưu thuế suất mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'country' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'priority' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        TaxRate::create([
            'name' => $validated['name'],
            'rate' => $validated['rate'],
            'country' => $validated['country'],
            'state' => $validated['state'] ?? null,
            'is_active' => $request->has('is_active'),
            'priority' => $validated['priority'] ?? 0,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.tax-rates.index')
            ->with('success', 'Thuế suất đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết thuế suất
     *
     * @param  \App\Models\TaxRate  $taxRate
     * @return \Illuminate\View\View
     */
    public function show(TaxRate $taxRate)
    {
        return view('admin.tax-rates.show', compact('taxRate'));
    }

    /**
     * Hiển thị form chỉnh sửa thuế suất
     *
     * @param  \App\Models\TaxRate  $taxRate
     * @return \Illuminate\View\View
     */
    public function edit(TaxRate $taxRate)
    {
        return view('admin.tax-rates.edit', compact('taxRate'));
    }

    /**
     * Cập nhật thuế suất
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaxRate  $taxRate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, TaxRate $taxRate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'country' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'priority' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $taxRate->update([
            'name' => $validated['name'],
            'rate' => $validated['rate'],
            'country' => $validated['country'],
            'state' => $validated['state'] ?? null,
            'is_active' => $request->has('is_active'),
            'priority' => $validated['priority'] ?? 0,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.tax-rates.index')
            ->with('success', 'Thuế suất đã được cập nhật thành công.');
    }

    /**
     * Xóa thuế suất
     *
     * @param  \App\Models\TaxRate  $taxRate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TaxRate $taxRate)
    {
        // Kiểm tra xem thuế suất có đang được sử dụng không
        // Nếu có, không cho phép xóa

        $taxRate->delete();

        return redirect()->route('admin.tax-rates.index')
            ->with('success', 'Thuế suất đã được xóa thành công.');
    }
}
