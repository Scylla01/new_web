<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        
        // Revenue this month
        $revenueThisMonth = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');
        
        // Orders today
        $ordersToday = Order::whereDate('created_at', Carbon::today())->count();
        
        // Pending orders
        $pendingOrders = Order::where('status', 'pending')->count();
        
        // Low stock products (less than 10)
        $lowStockProducts = Product::where('stock_quantity', '<', 10)
            ->where('is_active', true)
            ->count();
        
        // Recent orders
        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->take(10)
            ->get();
        
        // Top selling products
        $topProducts = Product::withCount(['orderItems as total_sold' => function ($query) {
                $query->selectRaw('sum(quantity)');
            }])
            ->orderBy('total_sold', 'desc')
            ->take(10)
            ->get();
        
        // Revenue chart data (last 30 days)
        $revenueChart = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'revenueThisMonth',
            'ordersToday',
            'pendingOrders',
            'lowStockProducts',
            'recentOrders',
            'topProducts',
            'revenueChart'
        ));
    }
}