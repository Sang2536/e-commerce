<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách danh mục.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View|object
     */
    public function index()
    {
        $categories = Category::withCount(['products', 'children'])
            ->with('parent')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Hiển thị form tạo danh mục mới.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View|object
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Lưu trữ danh mục mới.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories',
            'slug' => 'nullable|max:255|unique:categories',
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Xử lý upload ảnh
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Danh mục đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết danh mục.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View|object
     */
    public function show(Category $category)
    {
        $category->load(['parent', 'children'])
            ->loadCount(['products', 'children']);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Hiển thị form chỉnh sửa danh mục.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View|object
     */
    public function edit(Category $category)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Cập nhật danh mục.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'max:255', Rule::unique('categories')->ignore($category)],
            'slug' => ['nullable', 'max:255', Rule::unique('categories')->ignore($category)],
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Kiểm tra xem danh mục cha có phải là danh mục con của danh mục hiện tại không
        if (!empty($validated['parent_id']) && $category->id == $validated['parent_id']) {
            return redirect()
                ->back()
                ->withErrors(['parent_id' => 'Không thể chọn chính danh mục này làm danh mục cha.'])
                ->withInput();
        }

        // Xử lý upload ảnh
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Danh mục đã được cập nhật thành công.');
    }

    /**
     * Xóa danh mục.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        // Kiểm tra xem danh mục có sản phẩm không
        if ($category->products()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Không thể xóa danh mục vì còn chứa sản phẩm.');
        }

        // Kiểm tra xem danh mục có danh mục con không
        if ($category->children()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Không thể xóa danh mục vì còn chứa danh mục con.');
        }

        // Xóa ảnh liên quan
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Danh mục đã được xóa thành công.');
    }
}
