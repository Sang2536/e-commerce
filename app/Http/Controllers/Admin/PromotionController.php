<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    /**
     * Hiển thị danh sách khuyến mãi
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Promotion::query();

        // Tìm kiếm
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status != 'all') {
            $active = $request->status === 'active';
            $query->where('is_active', $active);
        }

        // Lọc theo thời gian
        if ($request->has('timeframe')) {
            $now = now();
            switch ($request->timeframe) {
                case 'active':
                    $query->where('start_date', '<=', $now)
                          ->where('end_date', '>=', $now);
                    break;
                case 'upcoming':
                    $query->where('start_date', '>', $now);
                    break;
                case 'expired':
                    $query->where('end_date', '<', $now);
                    break;
            }
        }

        $promotions = $query->orderBy('start_date', 'desc')->paginate(15);

        return view('admin.promotions.index', compact('promotions'));
    }

    /**
     * Hiển thị form tạo khuyến mãi mới
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();
        $products = Product::where('is_active', true)->get();
        return view('admin.promotions.create', compact('categories', 'products'));
    }

    /**
     * Lưu khuyến mãi mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:promotions',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Xử lý hình ảnh
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('promotions', 'public');
        }

        // Tạo khuyến mãi
        $promotion = Promotion::create([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'description' => $validated['description'] ?? null,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'min_purchase' => $validated['min_purchase'] ?? null,
            'max_discount' => $validated['max_discount'] ?? null,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'usage_count' => 0,
            'is_active' => $request->has('is_active'),
            'image' => $imagePath,
        ]);

        // Gán sản phẩm
        if ($request->has('products')) {
            $promotion->products()->attach($request->products);
        }

        // Gán danh mục
        if ($request->has('categories')) {
            $promotion->categories()->attach($request->categories);
        }

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Khuyến mãi đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết khuyến mãi
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\View\View
     */
    public function show(Promotion $promotion)
    {
        $promotion->load(['products', 'categories', 'orders']);
        return view('admin.promotions.show', compact('promotion'));
    }

    /**
     * Hiển thị form chỉnh sửa khuyến mãi
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\View\View
     */
    public function edit(Promotion $promotion)
    {
        $categories = Category::all();
        $products = Product::where('is_active', true)->get();
        $promotion->load(['products', 'categories']);

        return view('admin.promotions.edit', compact('promotion', 'categories', 'products'));
    }

    /**
     * Cập nhật khuyến mãi
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:promotions,code,' . $promotion->id,
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Xử lý hình ảnh
        if ($request->hasFile('image')) {
            // Xóa hình ảnh cũ
            if ($promotion->image) {
                Storage::disk('public')->delete($promotion->image);
            }
            $imagePath = $request->file('image')->store('promotions', 'public');
            $promotion->image = $imagePath;
        }

        // Cập nhật khuyến mãi
        $promotion->update([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'description' => $validated['description'] ?? null,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'min_purchase' => $validated['min_purchase'] ?? null,
            'max_discount' => $validated['max_discount'] ?? null,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'is_active' => $request->has('is_active'),
            'image' => $promotion->image,
        ]);

        // Cập nhật sản phẩm
        $promotion->products()->sync($request->products ?? []);

        // Cập nhật danh mục
        $promotion->categories()->sync($request->categories ?? []);

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Khuyến mãi đã được cập nhật thành công.');
    }

    /**
     * Xóa khuyến mãi
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Promotion $promotion)
    {
        // Xóa hình ảnh
        if ($promotion->image) {
            Storage::disk('public')->delete($promotion->image);
        }

        // Xóa liên kết với sản phẩm và danh mục
        $promotion->products()->detach();
        $promotion->categories()->detach();

        // Xóa khuyến mãi
        $promotion->delete();

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Khuyến mãi đã được xóa thành công.');
    }
}
