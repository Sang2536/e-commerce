<?php

/*
|--------------------------------------------------------------------------
| Notification Routes
|--------------------------------------------------------------------------
|
| Các route cho phần thông báo cho người dùng.
|
*/

Route::middleware(['auth'])->prefix('notifications')->group(function () {
    Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::put('/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read.all');
    Route::delete('/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
});
