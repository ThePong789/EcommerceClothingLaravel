@extends('layouts.admin')

@section('title', 'Reviews')
@section('page-title', 'Reviews')
@section('breadcrumb', 'Admin / Reviews')

@section('content')
@php $routePrefix = auth()->user()->isAdmin() ? 'admin' : 'staff'; @endphp
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Review</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td>
                        <div style="font-weight:500;font-size:.875rem;">{{ $review->review_title }}</div>
                        <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px;">{{ Str::limit($review->description, 60) }}</div>
                    </td>
                    <td style="font-size:.875rem;">{{ $review->user->username ?? 'N/A' }}</td>
                    <td style="font-size:.875rem;">{{ $review->product->product_name ?? 'General' }}</td>
                    <td>
                        <div style="color:#f59e0b;">
                            @for($i=1;$i<=5;$i++)<i class="fas fa-star" style="{{ $i > $review->rating ? 'color:#e5e7eb' : '' }}"></i>@endfor
                        </div>
                        <div style="font-size:.75rem;color:var(--text-muted);">{{ $review->rating }}/5</div>
                    </td>
                    <td>
                        <span class="badge {{ $review->is_approved ? 'badge-success' : 'badge-warning' }}">
                            {{ $review->is_approved ? 'Approved' : 'Pending' }}
                        </span>
                    </td>
                    <td style="color:var(--text-muted);font-size:.8rem;">{{ $review->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display:flex;gap:.4rem;">
                            @if(!$review->is_approved)
                            <form action="{{ route($routePrefix.'.reviews.approve', $review->review_id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-success btn-xs" type="submit"><i class="fas fa-check"></i></button>
                            </form>
                            @endif
                            <form action="{{ route($routePrefix.'.reviews.destroy', $review->review_id) }}" method="POST" onsubmit="return confirm('Delete review?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-xs" type="submit"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state"><i class="fas fa-star"></i><h3>No reviews yet</h3></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;">{{ $reviews->links() }}</div>
@endsection
