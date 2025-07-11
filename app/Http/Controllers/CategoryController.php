<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Hiển thị tất cả danh mục
     */
    public function index()
    {
        $categories = Category::withCount(['products' => function($query) {
            $query->where('is_active', true);
        }])->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Hiển thị danh mục và sản phẩm trong danh mục đó
     */
    public function show(Category $category)
    {
        $products = $category->products()
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('categories.show', compact('category', 'products'));
    }
}
