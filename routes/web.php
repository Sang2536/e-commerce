<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Đăng ký các route cho Auth
require __DIR__.'/auth.php';

// Đăng ký các route cho Notification
require __DIR__.'/notification.php';

// Đăng ký các route cho Admin
require __DIR__.'/admin.php';

// Đăng ký các route cho Api
require __DIR__.'/api.php';


Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search.index');
Route::get('/search/suggestions', [\App\Http\Controllers\SearchController::class, 'suggestions'])->name('search.suggestions');

Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');

Route::get('/reviews', [\App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
Route::get('/products/{product}/reviews', [\App\Http\Controllers\ReviewController::class, 'productReviews'])->name('products.reviews');

Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');

// Tích hợp mạng xã hội
Route::get('/auth/{provider}/redirect', [\App\Http\Controllers\SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [\App\Http\Controllers\SocialAuthController::class, 'callback'])->name('social.callback');

Route::get('/about', [\App\Http\Controllers\PageController::class, 'about'])->name('pages.about');
Route::get('/faq', [\App\Http\Controllers\PageController::class, 'faq'])->name('pages.faq');
Route::get('/terms', [\App\Http\Controllers\PageController::class, 'terms'])->name('pages.terms');
Route::get('/privacy', [\App\Http\Controllers\PageController::class, 'privacy'])->name('pages.privacy');

//  Các route cần quyền truy cập
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{item}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{product}', [\App\Http\Controllers\WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{product}', [\App\Http\Controllers\WishlistController::class, 'remove'])->name('wishlist.remove');

    Route::post('/products/{product}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failed', [\App\Http\Controllers\CheckoutController::class, 'failed'])->name('checkout.failed');
    Route::get('/payment/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');
    Route::post('/payment/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');

    // VNPay payment routes
    Route::get('/payment/vnpay/redirect', [\App\Http\Controllers\VNPayController::class, 'redirect'])->name('payment.vnpay.redirect');
    Route::get('/payment/vnpay/ipn', [\App\Http\Controllers\VNPayController::class, 'ipn'])->name('payment.vnpay.ipn');

    // MoMo payment routes
    Route::get('/payment/momo/redirect', [\App\Http\Controllers\MoMoController::class, 'redirect'])->name('payment.momo.redirect');
    Route::get('/payment/momo/ipn', [\App\Http\Controllers\MoMoController::class, 'ipn'])->name('payment.momo.ipn');
    Route::get('/payment/momo/return', [\App\Http\Controllers\MoMoController::class, 'return'])->name('payment.momo.return');

    // Các cổng thanh toán mới
    Route::get('/payment/method', [\App\Http\Controllers\PaymentController::class, 'selectMethod'])->name('payment.method');
    Route::post('/payment/process/{method}', [\App\Http\Controllers\PaymentController::class, 'processMethod'])->name('payment.process');
    Route::get('/payment/vnpay/return', [\App\Http\Controllers\PaymentGateways\VnPayController::class, 'return'])->name('payment.vnpay.return');

    // Theo dõi vận chuyển
    Route::get('/shipping/track/{order}', [\App\Http\Controllers\ShippingController::class, 'track'])->name('shipping.track');

    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');

    // Quản lý địa chỉ người dùng
    Route::get('/addresses', [\App\Http\Controllers\AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [\App\Http\Controllers\AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address}', [\App\Http\Controllers\AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [\App\Http\Controllers\AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::put('/addresses/{address}/default', [\App\Http\Controllers\AddressController::class, 'setDefault'])->name('addresses.default');
});
