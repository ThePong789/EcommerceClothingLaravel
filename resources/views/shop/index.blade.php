@extends('layouts.app')

@section('title', 'Shop')

@push('styles')
<style>
    .shop-layout { display: grid; grid-template-columns: 260px 1fr; gap: 2rem; padding: 3rem 0; }
    .filter-sidebar { position: sticky; top: 90px; align-self: start; }
    .filter-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.25rem; }
    .filter-card h4 { font-size: .85rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-bottom: 1rem; color: var(--black); }
    .filter-item { display: flex; align-items: center; gap: .5rem; padding: .35rem 0; cursor: pointer; font-size: .875rem; color: var(--charcoal); }
    .filter-item input[type=radio], .filter-item input[type=checkbox] { accent-color: var(--gold); width: 15px; height: 15px; }
    .filter-item a { text-decoration: none; color: inherit; display: flex; align-items: center; justify-content: space-between; width: 100%; transition: color .2s; }
    .filter-item a:hover { color: var(--gold); }
    .filter-item a.active { color: var(--gold); font-weight: 600; }
    .sort-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
    .sort-bar span { font-size: .875rem; color: var(--gray); }
    .products-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    .search-bar { display: flex; margin-bottom: 1rem; border: 1.5px solid var(--border); border-radius: 10px; overflow: hidden; background: #fff; transition: border-color .2s; }
.search-bar:focus-within { border-color: var(--gold); }
.search-bar input { flex: 1; padding: .6rem .9rem; border: none; outline: none; font-size: .875rem; font-family: var(--font-body); background: transparent; min-width: 0; }
.search-bar button { padding: .6rem 1.2rem; background: var(--black); color: #fff; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    @media(max-width:1024px) { .products-grid { grid-template-columns: repeat(2,1fr); } }
    @media(max-width:768px) { .shop-layout { grid-template-columns: 1fr; } .filter-sidebar { position: static; } .products-grid { grid-template-columns: repeat(2,1fr); } }
    @media(max-width:480px) { .products-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="page-hero">
    <h1>Shop</h1>
    <p>Explore our curated collection</p>
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> <span>/</span> <span>Shop</span></div>
</div>

<div class="container">
    <div class="shop-layout">
        <!-- SIDEBAR -->
        <aside class="filter-sidebar">
            <form method="GET" action="{{ route('shop') }}" id="filter-form">
                <div class="filter-card">
                    <h4>Search</h4>
                    <div class="search-bar">
                        <input type="text" name="search" placeholder="Product name…" value="{{ request('search') }}">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>

                <div class="filter-card">
                    <h4>Categories</h4>
                    <div class="filter-item">
                        <a href="{{ route('shop') }}" class="{{ !request('category') ? 'active' : '' }}">
                            All Categories <span style="font-size:.75rem;color:var(--gray);">{{ $categories->sum('products_count') ?? '' }}</span>
                        </a>
                    </div>
                    @foreach($categories as $cat)
                    <div class="filter-item">
                        <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="{{ request('category') == $cat->slug ? 'active' : '' }}">
                            {{ $cat->category_name }}
                            <span style="font-size:.75rem;color:var(--gray);">{{ $cat->products_count }}</span>
                        </a>
                    </div>
                    @endforeach
                </div>

                <div class="filter-card">
                    <h4>Sort By</h4>
                    @foreach(['newest' => 'Newest First', 'price_asc' => 'Price: Low to High', 'price_desc' => 'Price: High to Low'] as $val => $label)
                    <div class="filter-item">
                        <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;width:100%;">
                            <input type="radio" name="sort" value="{{ $val }}" {{ request('sort', 'newest') === $val ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()">
                            {{ $label }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </form>
        </aside>

        <!-- PRODUCTS -->
        <div>
            <div class="sort-bar">
                <span>{{ $products->total() }} products found</span>
                @if(request('search') || request('category'))
                    <a href="{{ route('shop') }}" style="font-size:.875rem;color:var(--gold);text-decoration:none;"><i class="fas fa-times-circle"></i> Clear filters</a>
                @endif
            </div>

            @if($products->isEmpty())
            <div style="text-align:center;padding:5rem 2rem;color:var(--gray);">
                <i class="fas fa-search" style="font-size:3rem;margin-bottom:1rem;display:block;opacity:.3;"></i>
                <h3 style="margin-bottom:.5rem;">No products found</h3>
                <p>Try adjusting your search or filters.</p>
                <a href="{{ route('shop') }}" class="btn btn-primary" style="margin-top:1.5rem;">Clear Filters</a>
            </div>
            @else
            <div class="products-grid">
                @foreach($products as $product)
                <div class="product-card">
                    <a href="{{ route('shop.show', $product->product_id) }}" style="text-decoration:none;">
                        <div class="product-card-img">
                            @if($product->product_image)
                                <img src="{{ asset('storage/'.$product->product_image) }}" alt="{{ $product->product_name }}">
                            @else
                                <i class="fas fa-tshirt"></i>
                            @endif
                        </div>
                    </a>
                    <div class="product-card-body">
                        <div class="product-card-cat">{{ $product->category->category_name ?? '' }}</div>
                        <a href="{{ route('shop.show', $product->product_id) }}" style="text-decoration:none;">
                            <h3 class="product-card-name">{{ $product->product_name }}</h3>
                        </a>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin:.5rem 0;">
                            <div class="product-card-price">
                                @if($product->sizes->isNotEmpty())
                                    @php $prices = $product->sizes->pluck('pivot.price'); @endphp
                                    @if($prices->min() == $prices->max())
                                        ${{ number_format($prices->min(), 2) }}
                                    @else
                                        ${{ number_format($prices->min(), 2) }} – ${{ number_format($prices->max(), 2) }}
                                    @endif
                                @else N/A @endif
                            </div>
                            <div class="stars">
                                @php $r = round($product->reviews->avg('rating') ?? 0); @endphp
                                @for($i=1;$i<=5;$i++)<i class="fas fa-star" style="{{ $i > $r ? 'color:#ddd' : '' }}"></i>@endfor
                                <span style="font-size:.75rem;color:var(--gray);margin-left:.2rem;">({{ $product->reviews->count() }})</span>
                            </div>
                        </div>
                        <a href="{{ route('shop.show', $product->product_id) }}" class="btn btn-outline" style="width:100%;justify-content:center;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            <div style="margin-top:2.5rem;">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
