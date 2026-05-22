<?php

namespace App\Http\Controllers;

use App\Models\{Cart, CartItem, Order, OrderItem, Payment, Shipping};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = Cart::with(['items.product', 'items.size'])->where('user_id', Auth::id())->first();
        if (!$cart || $cart->items->isEmpty()) return redirect()->route('cart')->with('error', 'Your cart is empty.');

        $addresses = Shipping::where('user_id', Auth::id())->get();
        return view('checkout.index', compact('cart', 'addresses'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_id'    => 'nullable|exists:shipping,shipping_id',
            'payment_method' => 'required|in:cod,aba,acleda',
        ]);

        $cart = Cart::with(['items.product', 'items.size'])->where('user_id', Auth::id())->first();
        if (!$cart || $cart->items->isEmpty()) return redirect()->route('cart');

        $orderId = null;

        DB::transaction(function () use ($request, $cart, &$orderId) {
            $shippingId = $request->shipping_id;

            if (!$shippingId && $request->has('new_address')) {
                $na = $request->new_address;
                $addr = Shipping::create([
                    'user_id'      => Auth::id(),
                    'full_name'    => $na['full_name'] ?? '',
                    'phone_number' => $na['phone_number'] ?? '',
                    'address'      => $na['address'] ?? '',
                    'province'     => $na['province'] ?? '',
                    'city'         => $na['city'] ?? null,
                    'postal_code'  => $na['postal_code'] ?? null,
                ]);
                $shippingId = $addr->shipping_id;
            }

            $total = $cart->items->sum(fn($i) => $i->price * $i->qty);

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id'      => Auth::id(),
                'shipping_id'  => $shippingId,
                'total_price'  => $total,
                'shipping_fee' => 0,
                'status'       => 'pending',
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'   => $order->order_id,
                    'product_id' => $item->product_id,
                    'size_id'    => $item->size_id,
                    'qty'        => $item->qty,
                    'price'      => $item->price,
                ]);
                DB::table('product_size')
                    ->where('product_id', $item->product_id)
                    ->where('size_id', $item->size_id)
                    ->decrement('stock_qty', $item->qty);
            }

            // For QR payment methods (aba / acleda), status stays 'pending' until confirmed
            $paymentStatus = ($request->payment_method === 'cod') ? 'pending' : 'awaiting_payment';

            Payment::create([
                'order_id'       => $order->order_id,
                'payment_method' => $request->payment_method,
                'amount'         => $total,
                'status'         => $paymentStatus,
            ]);

            $cart->items()->delete();
            $orderId = $order->order_id;
        });

        // Redirect to order detail so the user can see their QR confirmation
        $method = $request->payment_method;
        if ($method === 'aba' || $method === 'acleda') {
            return redirect()->route('orders.show', $orderId)
                ->with('success', 'Order placed! Please check your payment QR below.')
                ->with('show_payment_qr', true)
                ->with('payment_method', $method);
        }

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }

    public function index()
    {
        $orders = Order::with(['items.product', 'payment'])
            ->where('user_id', Auth::id())->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'items.size', 'shipping', 'payment'])
            ->where('user_id', Auth::id())->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function uploadReceipt(Request $request, $id)
    {
        $order = Order::with('payment')->where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $path = $request->file('receipt_image')->store('receipts', 'public');

        $order->payment->update(['receipt_image' => $path]);

        return back()->with('success', 'Receipt uploaded! The admin will review your payment shortly.');
    }
}
