@extends('layouts.app')

@section('title', 'Cart')

@push('styles')
<style>
    .cart-layout { display: grid; grid-template-columns: 1fr 360px; gap: 2.5rem; padding: 3rem 0; align-items: start; }
    .cart-table { background: #fff; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; }
    .cart-table table { width: 100%; border-collapse: collapse; }
    .cart-table thead th { background: var(--warm-white); padding: .9rem 1.25rem; text-align: left; font-size: .8rem; text-transform: uppercase; letter-spacing: .5px; font-weight: 600; color: var(--gray); border-bottom: 1px solid var(--border); }
    .cart-table tbody td { padding: 1.25rem; border-bottom: 1px solid #f5f5f5; vertical-align: middle; }
    .cart-table tbody tr:last-child td { border-bottom: none; }
    .cart-product { display: flex; align-items: center; gap: 1rem; }
    .cart-product-img { width: 72px; height: 72px; background: var(--warm-white); border-radius: 8px; overflow: hidden; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: var(--gray); }
    .cart-product-img img { width: 100%; height: 100%; object-fit: cover; }
    .cart-product-name { font-weight: 600; font-size: .9rem; color: var(--black); }
    .cart-product-size { font-size: .8rem; color: var(--gray); margin-top: .2rem; }
    .qty-control { display: inline-flex; align-items: center; border: 1.5px solid var(--border); border-radius: 6px; overflow: hidden; }
    .qty-control button { width: 32px; height: 32px; background: #fff; border: none; cursor: pointer; font-size: .95rem; color: var(--charcoal); }
    .qty-control button:hover { background: var(--warm-white); }
    .qty-control input { width: 40px; text-align: center; border: none; font-size: .875rem; font-family: var(--font-body); }
    .cart-summary { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; position: sticky; top: 90px; }
    .cart-summary h3 { font-family: var(--font-display); font-size: 1.25rem; margin-bottom: 1.5rem; }
    .summary-row { display: flex; justify-content: space-between; align-items: center; padding: .6rem 0; font-size: .9rem; }
    .summary-row.total { border-top: 1px solid var(--border); margin-top: .5rem; padding-top: 1rem; font-weight: 700; font-size: 1.05rem; }

    /* ── Mobile ── */
    @media(max-width: 768px) {
        .cart-layout { grid-template-columns: 1fr; padding: 1.5rem 0; }
        .cart-summary { position: static; }

        /* Hide desktop table */
        .cart-table { display: none; }

        /* Card-style mobile cart items */
        .cart-cards { display: flex; flex-direction: column; gap: .75rem; }
        .cart-card {
            background: #fff; border-radius: 12px; border: 1px solid var(--border);
            padding: 1rem; display: flex; gap: .9rem; align-items: flex-start;
        }
        .cart-card-img { width: 72px; height: 72px; border-radius: 8px; overflow: hidden; flex-shrink: 0; background: var(--warm-white); display: flex; align-items: center; justify-content: center; color: var(--gray); }
        .cart-card-img img { width: 100%; height: 100%; object-fit: cover; }
        .cart-card-body { flex: 1; min-width: 0; }
        .cart-card-name { font-weight: 600; font-size: .9rem; margin-bottom: .2rem; }
        .cart-card-size { font-size: .78rem; color: var(--gray); margin-bottom: .6rem; }
        .cart-card-footer { display: flex; align-items: center; justify-content: space-between; gap: .5rem; flex-wrap: wrap; }
        .cart-card-price { font-weight: 700; font-size: .95rem; }
        .cart-card-remove { background: none; border: none; color: #dc3545; cursor: pointer; font-size: .95rem; padding: .2rem; }
    }

    /* Show desktop table, hide mobile cards on large screens */
    @media(min-width: 769px) {
        .cart-cards { display: none; }
    }
</style>
@endpush

@section('content')
<div class="page-hero">
    <h1>Shopping Cart</h1>
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> <span>/</span> <span>Cart</span></div>
</div>

<div class="container">
    <div style="padding:3rem 0;">
        @if(!$cart || $cart->items->isEmpty())
            <div style="text-align:center;padding:6rem 2rem;">
                <i class="fas fa-shopping-bag" style="font-size:4rem;color:var(--gold-light);margin-bottom:1.5rem;display:block;"></i>
                <h2 style="font-family:var(--font-display);margin-bottom:.75rem;">Your cart is empty</h2>
                <p style="color:var(--gray);margin-bottom:2rem;">Add some products to get started.</p>
                <a href="{{ route('shop') }}" class="btn btn-primary" style="padding:.9rem 2.5rem;">Start Shopping</a>
            </div>
        @else
        <div class="cart-layout">
            <div>
                <!-- DESKTOP TABLE -->
                <div class="cart-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart->items as $item)
                            <tr>
                                <td>
                                    <div class="cart-product">
                                        <div class="cart-product-img">
                                            @if($item->product && $item->product->product_image)
                                                <img src="{{ asset('storage/'.$item->product->product_image) }}" alt="">
                                            @else <i class="fas fa-tshirt"></i> @endif
                                        </div>
                                        <div>
                                            <div class="cart-product-name">{{ $item->product->product_name ?? 'Product' }}</div>
                                            <div class="cart-product-size">Size: {{ $item->size->size_name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>
                                    <form action="{{ route('cart.update', $item->cart_item_id) }}" method="POST" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <div class="qty-control">
                                            <button type="submit" name="qty" value="{{ max(1, $item->qty - 1) }}">−</button>
                                            <input type="number" value="{{ $item->qty }}" min="1" readonly>
                                            <button type="submit" name="qty" value="{{ $item->qty + 1 }}">+</button>
                                        </div>
                                    </form>
                                </td>
                                <td><strong>${{ number_format($item->price * $item->qty, 2) }}</strong></td>
                                <td>
                                    <form action="{{ route('cart.remove', $item->cart_item_id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background:none;border:none;color:#dc3545;cursor:pointer;font-size:1rem;padding:.25rem;" title="Remove"><i class="fas fa-times"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE CARDS -->
                <div class="cart-cards">
                    @foreach($cart->items as $item)
                    <div class="cart-card">
                        <div class="cart-card-img">
                            @if($item->product && $item->product->product_image)
                                <img src="{{ asset('storage/'.$item->product->product_image) }}" alt="">
                            @else <i class="fas fa-tshirt"></i> @endif
                        </div>
                        <div class="cart-card-body">
                            <div class="cart-card-name">{{ $item->product->product_name ?? 'Product' }}</div>
                            <div class="cart-card-size">Size: {{ $item->size->size_name ?? 'N/A' }}</div>
                            <div class="cart-card-footer">
                                <form action="{{ route('cart.update', $item->cart_item_id) }}" method="POST" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <div class="qty-control">
                                        <button type="submit" name="qty" value="{{ max(1, $item->qty - 1) }}">−</button>
                                        <input type="number" value="{{ $item->qty }}" min="1" readonly>
                                        <button type="submit" name="qty" value="{{ $item->qty + 1 }}">+</button>
                                    </div>
                                </form>
                                <div class="cart-card-price">${{ number_format($item->price * $item->qty, 2) }}</div>
                                <form action="{{ route('cart.remove', $item->cart_item_id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="cart-card-remove" title="Remove"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div style="margin-top:1rem;">
                    <a href="{{ route('shop') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
                </div>
            </div>

            <!-- SUMMARY -->
            <div class="cart-summary">
                <h3>Order Summary</h3>
                @php $subtotal = $cart->items->sum(fn($i) => $i->price * $i->qty); @endphp
                <div class="summary-row"><span>Subtotal ({{ $cart->items->sum('qty') }} items)</span><span>${{ number_format($subtotal, 2) }}</span></div>
                <div class="summary-row"><span>Shipping</span><span style="color:var(--gray);">{{ $subtotal >= 100 ? 'FREE' : 'Calculated at checkout' }}</span></div>
                <div class="summary-row total"><span>Total</span><span>${{ number_format($subtotal, 2) }}</span></div>
                <a href="{{ route('checkout') }}" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:1.5rem;padding:.85rem;">
                    Proceed to Checkout <i class="fas fa-arrow-right"></i>
                </a>
                <div style="display:flex;justify-content:center;gap:1rem;margin-top:1rem;">
                    <i class="fas fa-lock" style="color:var(--gray);font-size:.8rem;"></i>
                    <span style="font-size:.75rem;color:var(--gray);">Secure checkout</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection