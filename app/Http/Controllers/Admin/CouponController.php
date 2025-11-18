<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::withCount('orders')->latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'description' => 'nullable|string',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        // Convert code to uppercase
        $validated['code'] = strtoupper($validated['code']);

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Mã giảm giá đã được tạo thành công!');
    }

    public function show(Coupon $coupon)
    {
        $coupon->load(['orders' => function ($query) {
            $query->latest()->take(10);
        }]);
        
        return view('admin.coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable|string',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        // Convert code to uppercase
        $validated['code'] = strtoupper($validated['code']);

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Mã giảm giá đã được cập nhật!');
    }

    public function destroy(Coupon $coupon)
    {
        // Check if coupon has been used
        if ($coupon->used_count > 0) {
            return back()->with('error', 'Không thể xóa mã giảm giá đã được sử dụng!');
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Mã giảm giá đã được xóa!');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();

        return back()->with('success', 'Trạng thái mã giảm giá đã được cập nhật!');
    }
}