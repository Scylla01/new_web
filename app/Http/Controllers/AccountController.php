<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Display user orders
     */
    public function orders()
    {
        $orders = auth()->user()->orders()
            ->with(['items.product', 'shippingAddress'])
            ->latest()
            ->paginate(10);

        return view('frontend.account.orders', compact('orders'));
    }

    /**
     * Display order detail
     */
    public function orderDetail(Order $order)
    {
        // Make sure user owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product', 'shippingAddress']);

        return view('frontend.account.order-detail', compact('order'));
    }

    /**
     * Cancel order
     */
    public function cancelOrder(Order $order)
    {
        // Make sure user owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$order->canCancel()) {
            return back()->with('error', 'Không thể hủy đơn hàng này!');
        }

        $order->update(['status' => 'cancelled']);

        // Restore product stock
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }

        return back()->with('success', 'Đã hủy đơn hàng thành công!');
    }

    /**
     * Display profile page
     */
    public function profile()
    {
        return view('frontend.account.profile');
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:6|confirmed',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Update basic info
        $user->name = $validated['name'];
        $user->phone = $validated['phone'];

        // Update password if provided
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.'])->withInput();
            }

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
        }

        $user->save();

        return back()->with('success', 'Đã cập nhật thông tin thành công!');
    }

    /**
     * Display addresses
     */
    public function addresses()
    {
        $addresses = auth()->user()->addresses()->get();

        return view('frontend.account.addresses', compact('addresses'));
    }

    /**
     * Add new address
     */
    public function addAddress(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address_detail' => 'required|string',
            'is_default' => 'nullable|boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_default'] = $request->has('is_default');

        Address::create($validated);

        return back()->with('success', 'Đã thêm địa chỉ mới!');
    }

    /**
     * Delete address
     */
    public function deleteAddress(Address $address)
    {
        // Make sure user owns this address
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $address->delete();

        return back()->with('success', 'Đã xóa địa chỉ!');
    }

    /**
     * Set default address
     */
    public function setDefaultAddress(Address $address)
    {
        // Make sure user owns this address
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        // Remove default from all addresses
        auth()->user()->addresses()->update(['is_default' => false]);

        // Set this address as default
        $address->update(['is_default' => true]);

        return back()->with('success', 'Đã đặt địa chỉ mặc định!');
    }
}