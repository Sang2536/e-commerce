<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Lấy thông tin profile người dùng
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $user = Auth::user();
        $user->load(['addresses', 'orders' => function($query) {
            $query->latest()->limit(5);
        }]);

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Cập nhật thông tin người dùng
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Kiểm tra mật khẩu hiện tại nếu người dùng muốn thay đổi mật khẩu
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu hiện tại không chính xác',
                    'errors' => [
                        'current_password' => ['Mật khẩu hiện tại không chính xác'],
                    ],
                ], 422);
            }
        }

        // Cập nhật thông tin cơ bản
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (isset($validated['phone'])) {
            $user->phone = $validated['phone'];
        }

        // Cập nhật mật khẩu nếu có
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Thông tin tài khoản đã được cập nhật',
            'data' => $user,
        ]);
    }
}
