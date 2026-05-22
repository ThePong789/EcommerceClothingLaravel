@extends('layouts.admin')

@section('title', 'Orders')
@section('page-title', 'Orders')
@section('breadcrumb', 'Admin / Orders')

@section('content')
@php $routePrefix = auth()->user()->isAdmin() ? 'admin' : 'staff'; @endphp
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <form method="GET" style="display:flex;gap:.75rem;">
        <input type="text" name="search" class="form-control" placeholder="Order number..." value="{{ request('search') }}" style="width:200px;">
        <select name="status" class="form-control" style="width:150px;">
            <option value="">All Status</option>
            @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline" type="submit"><i class="fas fa-search"></i></button>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Order No.</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                @php $colors = ['pending'=>'warning','processing'=>'info','shipped'=>'info','delivered'=>'success','cancelled'=>'danger']; @endphp
                <tr>
                    <td><strong>{{ $order->order_id }}</strong></td>
                    <td>{{ $order->user->username ?? 'N/A' }}</td>
                    <td><strong>${{ number_format($order->total_price, 2) }}</strong></td>
                    <td>
                        @if($order->payment)
                            <span class="badge badge-{{ $order->payment->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment->status) }}</span>
                            
                        @else <span class="badge badge-secondary">N/A</span> @endif
                    </td>
                    <td><span class="badge badge-{{ $colors[$order->status] ?? 'secondary' }}">{{ ucfirst($order->status) }}</span></td>
                    <td style="color:var(--text-muted);font-size:.8rem;">{{ $order->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route($routePrefix.'.orders.show', $order->order_id) }}" class="btn btn-outline btn-xs"><i class="fas fa-eye"></i> View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state"><i class="fas fa-shopping-cart"></i><h3>No orders found</h3></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;">{{ $orders->links() }}</div>
@endsection
