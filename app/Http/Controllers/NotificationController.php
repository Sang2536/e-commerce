<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Hiển thị danh sách thông báo
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Đánh dấu thông báo đã đọc
     *
     * @param  \Illuminate\Notifications\DatabaseNotification  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(DatabaseNotification $notification)
    {
        // Kiểm tra quyền sở hữu
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thông báo đã được đánh dấu là đã đọc.'
            ]);
        }

        return back()->with('success', 'Thông báo đã được đánh dấu là đã đọc.');
    }

    /**
     * Đánh dấu tất cả thông báo đã đọc
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tất cả thông báo đã được đánh dấu là đã đọc.'
            ]);
        }

        return back()->with('success', 'Tất cả thông báo đã được đánh dấu là đã đọc.');
    }

    /**
     * Xóa thông báo
     *
     * @param  \Illuminate\Notifications\DatabaseNotification  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DatabaseNotification $notification)
    {
        // Kiểm tra quyền sở hữu
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thông báo đã được xóa.'
            ]);
        }

        return back()->with('success', 'Thông báo đã được xóa.');
    }
}
