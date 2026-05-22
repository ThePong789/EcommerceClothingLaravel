<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Product, Order, Review, Category};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'    => User::where('role_id', 3)->count(),
            'total_products' => Product::count(),
            'total_orders'   => Order::count(),
            'total_revenue'  => Order::where('status', '!=', 'cancelled')->sum('total_price'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'low_stock'      => DB::table('product_size')->where('stock_qty', '<', 5)->count(),
        ];

        $recentOrders = Order::with(['user', 'payment'])->latest()->take(8)->get();

        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.product_id')
            ->select('products.product_name', DB::raw('SUM(order_items.qty) as total_sold'))
            ->groupBy('products.product_id', 'products.product_name')
            ->orderByDesc('total_sold')->take(5)->get();

        $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as revenue')
            ->whereYear('created_at', now()->year)
            ->where('status', '!=', 'cancelled')
            ->groupByRaw('MONTH(created_at)')
            ->pluck('revenue', 'month');

        $pendingReviews = Review::with('user')->where('is_approved', false)->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'monthlyRevenue', 'pendingReviews'));
    }
}
