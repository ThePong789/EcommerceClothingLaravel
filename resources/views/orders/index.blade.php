@extends('layouts.app')

@section('title', 'My Orders')

@push('styles')
<style>
    .orders-wrap { max-width: 860px; margin: 0 auto; padding: 3rem 0; }
    .order-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; margin-bottom: 1.25rem; overflow: hidden; transition: box-shadow .2s; }
    .order-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.06); }
    .order-card-header { padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); background: var(--warm-white); }
    .order-card-body { padding: 1.25rem 1.5rem; }
    .order-items-preview { display: flex; gap: .5rem; }
    .order-thumb { width: 52px; height: 52px; background: var(--warm-white); border-radius: 6px; overflow: hidden; display: flex; align-items: center; justify-content: center; color: var(--gray); font-size: .8rem; flex-shrink: 0; }
    .order-thumb img { width: 100%; height: 100%; object-fit: cover; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <h1>My Orders</h1>
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> <span>/</span> <span>Orders</span></div>
</div>
<div class="container">
    <div class="orders-wrap">
        @if($orders->isEmpty())
            <div style="text-align:center;padding:5rem 2rem;">
                <i class="fas fa-box-open" style="font-size:4rem;color:var(--gold-light);margin-bottom:1.5rem;display:block;"></i>
                <h2 style="font-family:var(--font-display);margin-bottom:.75rem;">No orders yet</h2>
                <p style="color:var(--gray);margin-bottom:2rem;">Start shopping and your orders will appear here.</p>
                <a href="{{ route('shop') }}" class="btn btn-primary" style="padding:.9rem 2.5rem;">Shop Now</a>
            </div>
        @else
            @foreach($orders as $order)
            @php $colors = ['pending'=>'warning','processing'=>'info','shipped'=>'info','delivered'=>'success','cancelled'=>'danger']; @endphp
            <div class="order-card">
                <div class="order-card-header">
                    <div>
                        <div style="font-weight:700;font-size:.9rem;">Order No.{{ $order->order_id }}</div>
                        <div style="font-size:.75rem;color:var(--gray);margin-top:2px;">{{ $order->created_at->format('F d, Y') }}</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:1rem;">
                        <span class="badge badge-{{ $colors[$order->status] ?? 'secondary' }}" style="font-size:.8rem;padding:.35rem .85rem;">{{ ucfirst($order->status) }}</span>
                        <a href="{{ route('orders.show', $order->order_id) }}" class="btn btn-outline btn-sm">View Details</a>
                    </div>
                </div>
                <div class="order-card-body">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <div class="order-items-preview">
                            @foreach($order->items->take(4) as $item)
                            <div class="order-thumb">
                                @if($item->product && $item->product->product_image)
                                    <img src="{{ asset('storage/'.$item->product->product_image) }}" alt="">
                                @else <i class="fas fa-tshirt"></i> @endif
                            </div>
                            @endforeach
                            @if($order->items->count() > 4)
                                <div class="order-thumb" style="background:var(--black);color:#fff;font-weight:600;">+{{ $order->items->count() - 4 }}</div>
                            @endif
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:.8rem;color:var(--gray);">{{ $order->items->sum('qty') }} item(s)</div>
                            <div style="font-weight:700;font-size:1.05rem;">${{ number_format($order->total_price, 2) }}</div>
                            @if($order->payment)<div style="font-size:.75rem;color:var(--gray);">via {{ ucfirst(str_replace('_',' ',$order->payment->payment_method)) }}</div>@endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div>{{ $orders->links() }}</div>
        @endif
    </div>
</div>
@endsection
