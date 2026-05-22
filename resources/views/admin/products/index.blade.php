@extends('layouts.admin')

@section('title', 'Products')
@section('page-title', 'Products')
@section('breadcrumb', 'Admin / Products')

@section('content')
@php $routePrefix = auth()->user()->isAdmin() ? 'admin' : 'staff'; @endphp
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <div>
        <form method="GET" style="display:flex;gap:.75rem;">
            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}" style="width:240px;">
            <select name="category" class="form-control" style="width:160px;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->category_id }}" {{ request('category') == $cat->category_id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                @endforeach
            </select>
            <select name="active" class="form-control" style="width:130px;">
                <option value="">All Status</option>
                <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button class="btn btn-outline" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <a href="{{ route($routePrefix.'.products.create') }}" class="btn btn-accent"><i class="fas fa-plus"></i> Add Product</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Sizes / Price From</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            <div style="width:44px;height:44px;background:var(--bg);border-radius:8px;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                                @if($product->product_image)
                                    <img src="{{ asset('storage/'.$product->product_image) }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <i class="fas fa-tshirt"></i>
                                @endif
                            </div>
                            <div>
                                <div style="font-weight:500;font-size:.875rem;">{{ $product->product_name }}</div>
                                @if($product->is_featured)<span class="badge badge-info" style="margin-top:2px;">Featured</span>@endif
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-secondary">{{ $product->category->category_name ?? 'N/A' }}</span></td>
                    <td>
                        {{ $product->sizes->count() }} sizes
                        @if($product->sizes->isNotEmpty())
                            <br><small style="color:var(--text-muted);">from ${{ number_format($product->sizes->min('pivot.price'), 2) }}</small>
                        @endif
                    </td>
                    <td>
                        @php $total = $product->sizes->sum('pivot.stock_qty'); @endphp
                        <span class="badge {{ $total < 5 ? 'badge-danger' : ($total < 20 ? 'badge-warning' : 'badge-success') }}">{{ $total }}</span>
                    </td>
                    <td><span class="badge {{ $product->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $product->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td style="color:var(--text-muted);font-size:.8rem;">{{ $product->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display:flex;gap:.4rem;">
                            <a href="{{ route($routePrefix.'.products.edit', $product->product_id) }}" class="btn btn-outline btn-xs"><i class="fas fa-edit"></i></a>
                            <form action="{{ route($routePrefix.'.products.destroy', $product->product_id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-xs" type="submit"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <h3>No products found</h3>
                            <p>Add your first product to get started.</p>
                            <a href="{{ route($routePrefix.'.products.create') }}" class="btn btn-accent" style="margin-top:1rem;">Add Product</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:1rem;">{{ $products->links() }}</div>

@endsection
