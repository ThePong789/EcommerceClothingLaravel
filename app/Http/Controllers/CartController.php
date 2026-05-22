<?php

namespace App\Http\Controllers;

use App\Models\{Cart, CartItem, Product, Size};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function getOrCreateCart()
    {
        return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }

    public function index()
    {
        $cart = Cart::with(['items.product', 'items.size'])
            ->where('user_id', Auth::id())->first();
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'size_id'    => 'required|exists:sizes,size_id',
            'qty'        => 'required|integer|min:1',
        ]);

        $productSize = DB::table('product_size')
            ->where('product_id', $request->product_id)
            ->where('size_id', $request->size_id)->first();

        if (!$productSize || $productSize->stock_qty < $request->qty) {
            return back()->with('error', 'Insufficient stock.');
        }

        $cart = $this->getOrCreateCart();
        $item = CartItem::where('cart_id', $cart->cart_id)
            ->where('product_id', $request->product_id)
            ->where('size_id', $request->size_id)->first();

        if ($item) {
            $item->qty += $request->qty;
            $item->save();
        } else {
            CartItem::create([
                'cart_id'    => $cart->cart_id,
                'product_id' => $request->product_id,
                'size_id'    => $request->size_id,
                'qty'        => $request->qty,
                'price'      => $productSize->price,
            ]);
        }

        return back()->with('success', 'Item added to cart!');
    }

    public function update(Request $request, $itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $item->qty = max(1, $request->qty);
        $item->save();
        return back()->with('success', 'Cart updated.');
    }

    public function remove($itemId)
    {
        CartItem::findOrFail($itemId)->delete();
        return back()->with('success', 'Item removed.');
    }
}
