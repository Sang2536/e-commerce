<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Hiển thị trang tìm kiếm với kết quả
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $category = $request->input('category');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sort = $request->input('sort', 'relevance');

        $productsQuery = Product::query()
            ->where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });

        if ($category) {
            $productsQuery->whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category);
            });
        }

        if ($minPrice) {
            $productsQuery->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $productsQuery->where('price', '<=', $maxPrice);
        }

        // Sắp xếp kết quả
        switch ($sort) {
            case 'price_asc':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'newest':
                $productsQuery->orderBy('created_at', 'desc');
                break;
            case 'rating':
                $productsQuery->withAvg('reviews', 'rating')
                            ->orderByDesc('reviews_avg_rating');
                break;
            default: // relevance
                // Mặc định đã sắp xếp theo liên quan
                break;
        }

        $products = $productsQuery->paginate(12)->withQueryString();

        return view('search.index', [
            'products' => $products,
            'query' => $query,
            'selectedCategory' => $category,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'sort' => $sort
        ]);
    }

    /**
     * Trả về gợi ý tìm kiếm dựa trên từ khóa nhập vào
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function suggestions(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = Product::where('name', 'like', "%{$query}%")
            ->where('status', 'active')
            ->select('id', 'name', 'slug', 'thumbnail')
            ->limit(5)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'url' => route('products.show', $product->slug),
                    'thumbnail' => $product->thumbnail
                ];
            });

        return response()->json($suggestions);
    }
}
