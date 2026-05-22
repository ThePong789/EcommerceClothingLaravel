<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\{Order, Product, Review};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending_orders'    => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'shipped_orders'    => Order::where('status', 'shipped')->count(),
            'low_stock'         => DB::table('product_size')->where('stock_qty', '<', 5)->count(),
        ];
        $recentOrders   = Order::with(['user', 'payment'])->latest()->take(10)->get();
        $pendingReviews = Review::with(['user', 'product'])->where('is_approved', false)->take(5)->get();
        return view('staff.dashboard', compact('stats', 'recentOrders', 'pendingReviews'));
    }
}
