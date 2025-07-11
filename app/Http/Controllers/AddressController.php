<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Hiển thị danh sách địa chỉ
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $addresses = Address::where('user_id', Auth::id())->get();

        return view('addresses.index', compact('addresses'));
    }

    /**
     * Lưu địa chỉ mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_default'] = $request->has('is_default');

        // Nếu đây là địa chỉ mặc định, cập nhật tất cả các địa chỉ khác thành không mặc định
        if ($validated['is_default']) {
            Address::where('user_id', Auth::id())
                ->update(['is_default' => false]);
        } else {
            // Nếu đây là địa chỉ đầu tiên, đặt làm mặc định
            $count = Address::where('user_id', Auth::id())->count();
            if ($count === 0) {
                $validated['is_default'] = true;
            }
        }

        Address::create($validated);

        return redirect()->route('addresses.index')
            ->with('success', 'Địa chỉ đã được thêm thành công.');
    }

    /**
     * Cập nhật địa chỉ
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Address $address)
    {
        // Kiểm tra quyền sở hữu
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        $validated['is_default'] = $request->has('is_default');

        // Nếu đây là địa chỉ mặc định, cập nhật tất cả các địa chỉ khác thành không mặc định
        if ($validated['is_default']) {
            Address::where('user_id', Auth::id())
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->route('addresses.index')
            ->with('success', 'Địa chỉ đã được cập nhật thành công.');
    }

    /**
     * Xóa địa chỉ
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Address $address)
    {
        // Kiểm tra quyền sở hữu
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $isDefault = $address->is_default;
        $address->delete();

        // Nếu địa chỉ bị xóa là mặc định, cập nhật địa chỉ đầu tiên thành mặc định
        if ($isDefault) {
            $firstAddress = Address::where('user_id', Auth::id())->first();
            if ($firstAddress) {
                $firstAddress->update(['is_default' => true]);
            }
        }

        return redirect()->route('addresses.index')
            ->with('success', 'Địa chỉ đã được xóa thành công.');
    }

    /**
     * Đặt địa chỉ làm mặc định
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setDefault(Address $address)
    {
        // Kiểm tra quyền sở hữu
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        // Cập nhật tất cả các địa chỉ thành không mặc định
        Address::where('user_id', Auth::id())
            ->update(['is_default' => false]);

        // Đặt địa chỉ hiện tại làm mặc định
        $address->update(['is_default' => true]);

        return redirect()->route('addresses.index')
            ->with('success', 'Địa chỉ mặc định đã được cập nhật.');
    }
}
