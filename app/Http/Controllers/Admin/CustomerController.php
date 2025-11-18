<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount('orders')
            ->withSum('orders as total_spent', 'total_amount');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        // Make sure it's a customer
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->load(['orders' => function ($query) {
            $query->latest()->take(10);
        }, 'addresses', 'reviews']);

        // Calculate statistics
        $stats = [
            'total_orders' => $customer->orders()->count(),
            'total_spent' => $customer->orders()->sum('total_amount'),
            'pending_orders' => $customer->orders()->where('status', 'pending')->count(),
            'completed_orders' => $customer->orders()->where('status', 'delivered')->count(),
        ];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    public function destroy(User $customer)
    {
        // Make sure it's a customer
        if ($customer->role !== 'customer') {
            abort(404);
        }

        // Check if customer has orders
        if ($customer->orders()->count() > 0) {
            return back()->with('error', 'Không thể xóa khách hàng có đơn hàng!');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Khách hàng đã được xóa!');
    }
}