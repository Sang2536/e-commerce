<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Lấy danh sách danh mục
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::withCount(['products' => function($query) {
            $query->where('is_active', true);
        }])->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
}
