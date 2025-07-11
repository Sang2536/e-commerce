<?php

// Cửa hàng API cho ứng dụng di động
Route::prefix('shop')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);
    Route::get('/products/{product}', [\App\Http\Controllers\Api\ProductController::class, 'show']);
    Route::get('/categories', [\App\Http\Controllers\Api\CategoryController::class, 'index']);
    Route::get('/user/profile', [\App\Http\Controllers\Api\UserController::class, 'profile']);
    Route::put('/user/profile', [\App\Http\Controllers\Api\UserController::class, 'updateProfile']);
});
