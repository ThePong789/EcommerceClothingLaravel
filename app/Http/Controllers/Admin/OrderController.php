<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment']);
        if ($request->status) $query->where('status', $request->status);
        if ($request->search) $query->where('order_number', 'like', '%'.$request->search.'%');
        $orders = $query->latest()->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'items.size', 'shipping', 'user', 'payment'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,processing,shipped,delivered,cancelled']);
        Order::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated.');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate(['payment_status' => 'required|in:pending,paid,failed,refunded']);
        $order = Order::with('payment')->findOrFail($id);

        if (!$order->payment) {
            return back()->with('error', 'No payment record found for this order.');
        }

        $data = ['status' => $request->payment_status];
        if ($request->payment_status === 'paid' && !$order->payment->paid_at) {
            $data['paid_at'] = now();
        }

        $order->payment->update($data);
        return back()->with('success', 'Payment status updated.');
    }
}
