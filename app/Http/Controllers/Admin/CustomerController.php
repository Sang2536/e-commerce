<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:customers',
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:255',
            'city'      => 'nullable|string|max:100',
            'state'     => 'nullable|string|max:100',
            'zip_code'  => 'nullable|string|max:20',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Customer::create($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Khách hàng đã được tạo.');
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.form', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'email', Rule::unique('customers')->ignore($customer->id)],
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:255',
            'city'      => 'nullable|string|max:100',
            'state'     => 'nullable|string|max:100',
            'zip_code'  => 'nullable|string|max:20',
            'password'  => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $customer->update($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Khách hàng đã được cập nhật.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Khách hàng đã được xoá.');
    }
}
