<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Tìm kiếm
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Lọc theo vai trò
        if ($request->has('role') && $request->role != 'all') {
            if ($request->role === 'admin') {
                $query->where('is_admin', true);
            } else {
                $query->where('is_admin', false);
            }
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status != 'all') {
            $active = $request->status === 'active';
            $query->where('is_active', $active);
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Hiển thị form tạo người dùng mới
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.users.form');
    }

    /**
     * Lưu người dùng mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'is_admin' => $request->has('is_admin'),
            'is_active' => $request->has('is_active'),
            'email_verified_at' => $request->has('is_active') ? now() : null,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết người dùng
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $user->load(['addresses']);
        $orders = $user->orders()->latest()->paginate(5);
        $reviews = $user->reviews()->latest()->paginate(5);

        return view('admin.users.show', compact('user', 'orders', 'reviews'));
    }

    /**
     * Hiển thị form chỉnh sửa người dùng
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    /**
     * Cập nhật người dùng
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->is_admin = $request->has('is_admin');
        $user->is_active = $request->has('is_active');

        // Cập nhật mật khẩu nếu có
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được cập nhật thành công.');
    }

    /**
     * Xóa người dùng
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Ngăn chặn xóa chính mình
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể xóa tài khoản của chính mình.');
        }

        // Kiểm tra xem người dùng có đơn hàng không
        if ($user->orders()->count() > 0) {
            return back()->with('error', 'Không thể xóa người dùng có đơn hàng. Hãy vô hiệu hóa tài khoản thay vì xóa.');
        }

        // Xóa các đánh giá, địa chỉ của người dùng
        $user->reviews()->delete();
        $user->addresses()->delete();
        $user->notifications()->delete();

        // Xóa người dùng
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được xóa thành công.');
    }
}
