<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\OrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        // Auto update payment status when delivered
        if ($validated['status'] === 'delivered' && $order->payment_method === 'cod') {
            $order->update(['payment_status' => 'paid']);
        }

        // Send email notification when status changes to confirmed or shipping
        if (in_array($validated['status'], ['confirmed', 'shipping', 'delivered']) && $oldStatus !== $validated['status']) {
            try {
                Mail::to($order->user->email)->send(new OrderConfirmation($order));
            } catch (\Exception $e) {
                \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            }
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
    
    /**
     * Resend order confirmation email
     */
    public function resendEmail(Order $order)
    {
        try {
            Mail::to($order->user->email)->send(new OrderConfirmation($order));
            return back()->with('success', 'Email xác nhận đã được gửi lại!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể gửi email: ' . $e->getMessage());
        }
    }
}