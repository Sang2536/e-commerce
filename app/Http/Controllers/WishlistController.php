<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Hiển thị danh sách yêu thích
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with('product')
            ->paginate(12);

        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * Thêm sản phẩm vào danh sách yêu thích
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Product $product)
    {
        // Kiểm tra xem sản phẩm đã có trong danh sách yêu thích chưa
        $existingItem = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if (!$existingItem) {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id
            ]);

            $message = 'Sản phẩm đã được thêm vào danh sách yêu thích.';
        } else {
            $message = 'Sản phẩm đã có trong danh sách yêu thích.';
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Xóa sản phẩm khỏi danh sách yêu thích
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Product $product)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được xóa khỏi danh sách yêu thích.'
            ]);
        }

        return back()->with('success', 'Sản phẩm đã được xóa khỏi danh sách yêu thích.');
    }
}
