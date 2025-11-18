<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'shippingAddress']);

        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'shippingAddress', 'items.product', 'coupon']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,shipping,delivered,cancelled',
        ]);

        // Don't allow status change if already delivered or cancelled
        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return back()->with('error', 'Không thể thay đổi trạng thái đơn hàng này!');
        }

        $order->update(['status' => $validated['status']]);

        // Auto update payment status when delivered
        if ($validated['status'] === 'delivered' && $order->payment_method === 'cod') {
            $order->update(['payment_status' => 'paid']);
        }

        return back()->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:unpaid,paid,refunded',
        ]);

        $order->update(['payment_status' => $validated['payment_status']]);

        return back()->with('success', 'Trạng thái thanh toán đã được cập nhật!');
    }
}