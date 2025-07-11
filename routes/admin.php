<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShippingMethodController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\TaxRateController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Các route cho phần quản trị website.
|
*/

//Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Customers
    Route::resource('customers', CustomerController::class);

    // Contacts
    Route::resource('contacts', ContactController::class);

    // Products
    Route::resource('products', ProductController::class);

    // Orders
    Route::resource('orders', OrderController::class);

    // Users
    Route::resource('users', UserController::class);

    // Reviews
    Route::resource('reviews', ReviewController::class);
    Route::put('reviews/{review}/toggle-approval', [ReviewController::class, 'toggleApproval'])->name('reviews.toggle-approval');

    // Promotion
    Route::resource('promotions', PromotionController::class);

    // Discount
    Route::resource('discounts', DiscountController::class);

    // Shipping Method
    Route::resource('shipping-methods', ShippingMethodController::class);

    // Payment Method
    Route::resource('payment-methods', PaymentMethodController::class);

    // Tax Rate
    Route::resource('tax-rates', TaxRateController::class);

    // Report
    Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
    Route::get('reports/marketing', [ReportController::class, 'marketing'])->name('reports.marketing');

    // System
    Route::get('system-logs', [SystemController::class, 'logs'])->name('system.logs');
    Route::get('system-health', [SystemController::class, 'health'])->name('system.health');

    // Setting
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('settings/general', [SettingController::class, 'general'])->name('settings.general');
    Route::get('settings/payment', [SettingController::class, 'payment'])->name('settings.payment');
    Route::get('settings/shipping', [SettingController::class, 'shipping'])->name('settings.shipping');
    Route::get('settings/seo', [SettingController::class, 'seo'])->name('settings.seo');
    Route::get('settings/mail', [SettingController::class, 'mail'])->name('settings.mail');
    Route::put('settings/mail', [SettingController::class, 'updateMail'])->name('settings.mail.update');
});
