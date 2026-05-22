@extends('layouts.admin')

@section('title', 'Staff Dashboard')
@section('page-title', 'Staff Dashboard')
@section('breadcrumb', 'Overview')

@section('content')
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-val">{{ $stats['pending_orders'] }}</div>
            <div class="stat-label">Pending Orders</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-cogs"></i></div>
        <div>
            <div class="stat-val">{{ $stats['processing_orders'] }}</div>
            <div class="stat-label">Processing</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-truck"></i></div>
        <div>
            <div class="stat-val">{{ $stats['shipped_orders'] }}</div>
            <div class="stat-label">Shipped</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
        <div>
            <div class="stat-val">{{ $stats['low_stock'] }}</div>
            <div class="stat-label">Low Stock Items</div>
        </div>
    </div>
</div>

<div class="row col-2">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-shopping-cart" style="color:var(--accent);margin-right:.5rem;"></i> Recent Orders</h3>
            <a href="{{ route('staff.orders.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    @php $colors = ['pending'=>'warning','processing'=>'info','shipped'=>'info','delivered'=>'success','cancelled'=>'danger']; @endphp
                    <tr>
                        <td><strong>#{{ $order->order_id }}</strong></td>
                        <td>{{ $order->user->username ?? 'N/A' }}</td>
                        <td>${{ number_format($order->total_price, 2) }}</td>
                        <td><span class="badge badge-{{ $colors[$order->status] ?? 'secondary' }}">{{ ucfirst($order->status) }}</span></td>
                        <td><a href="{{ route('staff.orders.show', $order->order_id) }}" class="btn btn-outline btn-xs">View</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--text-muted);">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-star" style="color:var(--accent);margin-right:.5rem;"></i> Pending Reviews</h3>
            <a href="{{ route('staff.reviews.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0;">
            @forelse($pendingReviews as $r)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:.85rem 1.25rem;border-bottom:1px solid var(--border);">
                <div>
                    <div style="font-size:.875rem;font-weight:500;">{{ $r->review_title }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted);">{{ $r->user->username ?? 'N/A' }} · {{ $r->product->product_name ?? 'General' }}</div>
                    <div style="color:#f59e0b;font-size:.75rem;margin-top:2px;">
                        @for($i=1;$i<=5;$i++)<i class="fas fa-star" style="{{ $i > $r->rating ? 'color:#e5e7eb' : '' }}"></i>@endfor
                    </div>
                </div>
                <form action="{{ route('staff.reviews.approve', $r->review_id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="btn btn-success btn-xs" type="submit"><i class="fas fa-check"></i> Approve</button>
                </form>
            </div>
            @empty
            <div style="padding:2rem;text-align:center;color:var(--text-muted);font-size:.875rem;">No pending reviews.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
