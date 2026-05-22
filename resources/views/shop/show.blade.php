@extends('layouts.app')

@section('title', $product->product_name)

@push('styles')
<style>
    .product-detail { padding: 4rem 0; }
    .product-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: start; }
    .product-img-wrap { position: sticky; top: 90px; }
    .product-main-img { width: 100%; aspect-ratio: 1; background: var(--warm-white); border-radius: 16px; overflow: hidden; display: flex; align-items: center; justify-content: center; font-size: 6rem; color: var(--gray); }
    .product-main-img img { width: 100%; height: 100%; object-fit: cover; }
    .product-info h1 { font-family: var(--font-display); font-size: 2rem; color: var(--black); margin-bottom: .5rem; line-height: 1.3; }
    .product-cat-badge { display: inline-block; background: var(--gold-light); color: #8a6d20; padding: .25rem .75rem; border-radius: 50px; font-size: .75rem; font-weight: 600; margin-bottom: 1rem; }
    .product-rating { display: flex; align-items: center; gap: .5rem; margin-bottom: 1.5rem; }
    .product-rating .stars { font-size: 1rem; }
    .product-rating span { font-size: .875rem; color: var(--gray); }
    .product-price { font-family: var(--font-display); font-size: 2rem; color: var(--black); margin-bottom: 1.5rem; }
    .product-desc { color: var(--gray); line-height: 1.8; font-size: .95rem; margin-bottom: 2rem; border-top: 1px solid var(--border); padding-top: 1.5rem; }
    .size-label { font-size: .85rem; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; margin-bottom: .75rem; display: block; }
    .size-options { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: 1.5rem; }
    .size-btn { padding: .5rem 1.1rem; border: 1.5px solid var(--border); border-radius: 6px; font-size: .875rem; cursor: pointer; background: #fff; color: var(--charcoal); font-family: var(--font-body); transition: all .2s; }
    .size-btn:hover { border-color: var(--black); }
    .size-btn.active { border-color: var(--black); background: var(--black); color: #fff; }
    .size-btn:disabled { opacity: .4; cursor: not-allowed; }
    .qty-wrap { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
    .qty-control { display: flex; align-items: center; border: 1.5px solid var(--border); border-radius: 8px; overflow: hidden; }
    .qty-control button { width: 38px; height: 38px; background: #fff; border: none; cursor: pointer; font-size: 1rem; color: var(--charcoal); transition: background .2s; }
    .qty-control button:hover { background: var(--warm-white); }
    .qty-control input { width: 50px; text-align: center; border: none; font-size: .95rem; font-family: var(--font-body); padding: 0; }
    .qty-control input:focus { outline: none; }
    .add-cart-btn { flex: 1; }
    .stock-info { font-size: .8rem; color: var(--gray); margin-top: .25rem; }
    .stock-info.low { color: #dc3545; }
    .tab-nav { display: flex; gap: 0; border-bottom: 1px solid var(--border); margin: 4rem 0 2rem; }
    .tab-btn { padding: .75rem 1.5rem; border: none; background: none; font-family: var(--font-body); font-size: .9rem; font-weight: 500; cursor: pointer; color: var(--gray); border-bottom: 2px solid transparent; margin-bottom: -1px; transition: all .2s; }
    .tab-btn.active { color: var(--black); border-bottom-color: var(--black); }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .review-item { padding: 1.5rem 0; border-bottom: 1px solid var(--border); }
    .review-item:last-child { border-bottom: none; }
    .related-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.5rem; padding: 3rem 0; }
    @media(max-width:1024px) { .product-layout { grid-template-columns: 1fr; gap: 2rem; } .product-img-wrap { position: static; } .related-grid { grid-template-columns: repeat(1,1fr); } }
</style>
@endpush

@section('content')
<div class="page-hero" style="padding:2.5rem;">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> <span>/</span>
        <a href="{{ route('shop') }}">Shop</a> <span>/</span>
        <span>{{ $product->product_name }}</span>
    </div>
</div>

<div class="container">
    <div class="product-detail">
        <div class="product-layout">
            <!-- IMAGE -->
            <div class="product-img-wrap">
                <div class="product-main-img">
                    @if($product->product_image)
                        <img src="{{ asset('storage/'.$product->product_image) }}" alt="{{ $product->product_name }}" id="main-img">
                    @else
                        <i class="fas fa-tshirt"></i>
                    @endif
                </div>
            </div>

            <!-- INFO -->
            <div class="product-info">
                <span class="product-cat-badge">{{ $product->category->category_name ?? '' }}</span>
                @if($product->is_featured)<span class="badge badge-gold" style="margin-left:.4rem;">Featured</span>@endif
                <h1>{{ $product->product_name }}</h1>

                <div class="product-rating">
                    <div class="stars">
                        @php $avgRating = round($product->reviews->where('is_approved',true)->avg('rating') ?? 0); @endphp
                        @for($i=1;$i<=5;$i++)<i class="fas fa-star" style="{{ $i > $avgRating ? 'color:#ddd' : 'color:var(--gold)' }}"></i>@endfor
                    </div>
                    <span>{{ $product->reviews->where('is_approved',true)->count() }} reviews</span>
                </div>

                <div class="product-price" id="displayed-price">
                    @if($product->sizes->isNotEmpty())
                        From ${{ number_format($product->sizes->min('pivot.price'), 2) }}
                    @else N/A @endif
                </div>

                @if($product->sizes->isNotEmpty())
                <form action="{{ route('cart.add') }}" method="POST" id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                    <input type="hidden" name="size_id" id="selected-size-id" value="">

                    <span class="size-label">Select Size</span>
                    <div class="size-options">
                        @foreach($product->sizes as $size)
                        <button type="button"
                            class="size-btn {{ $size->pivot->stock_qty == 0 ? '' : '' }}"
                            data-size-id="{{ $size->size_id }}"
                            data-price="{{ $size->pivot->price }}"
                            data-stock="{{ $size->pivot->stock_qty }}"
                            {{ $size->pivot->stock_qty == 0 ? 'disabled' : '' }}
                            onclick="selectSize(this)">
                            {{ $size->size_name }}
                            @if($size->pivot->stock_qty == 0)<br><small style="font-size:.65rem;">Out</small>@endif
                        </button>
                        @endforeach
                    </div>
                    <div id="stock-info" class="stock-info" style="margin-bottom:1rem;display:none;"></div>

                    <div class="qty-wrap">
                        <div class="qty-control">
                            <button type="button" onclick="changeQty(-1)">−</button>
                            <input type="number" name="qty" id="qty-input" value="1" min="1" max="99">
                            <button type="button" onclick="changeQty(1)">+</button>
                        </div>
                        <button type="submit" class="btn btn-primary add-cart-btn" id="add-cart-btn" style="justify-content:center;" disabled>
                            <i class="fas fa-shopping-bag"></i> Add to Cart
                        </button>
                    </div>
                </form>
                @else
                <div style="color:var(--gray);padding:1rem;background:var(--warm-white);border-radius:8px;">Currently out of stock.</div>
                @endif

                <div style="display:flex;gap:.75rem;margin-top:.5rem;padding-top:1.5rem;border-top:1px solid var(--border);">
                    <div style="display:flex;align-items:center;gap:.4rem;font-size:.8rem;color:var(--gray);"><i class="fas fa-truck" style="color:var(--gold);"></i> Free shipping over $100</div>
                    <div style="display:flex;align-items:center;gap:.4rem;font-size:.8rem;color:var(--gray);"><i class="fas fa-undo" style="color:var(--gold);"></i> 30-day returns</div>
                </div>

                @if($product->description)
                <div class="product-desc">{{ $product->description }}</div>
                @endif
            </div>
        </div>

        <!-- TABS -->
        <div class="tab-nav">
            <button class="tab-btn active" onclick="switchTab('reviews', this)">Reviews ({{ $product->reviews->where('is_approved',true)->count() }})</button>
            @auth
                @if(!$product->reviews->where('user_id', auth()->id())->count())
                <button class="tab-btn" onclick="switchTab('write-review', this)">Write a Review</button>
                @endif
            @else
            <button class="tab-btn" onclick="switchTab('write-review', this)">Write a Review</button>
            @endauth
        </div>

        <div id="tab-reviews" class="tab-content active">
            @auth
                @php $myPending = $product->reviews->where('is_approved', false)->where('user_id', auth()->id())->first(); @endphp
                @if($myPending)
                <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:8px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:.875rem;color:#856404;">
                    <i class="fas fa-clock"></i> <strong>Your review is awaiting approval</strong> — it will appear publicly once approved by our team.
                </div>
                @endif
            @endauth
            @forelse($product->reviews->where('is_approved',true) as $review)
            <div class="review-item">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem;">
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <div style="width:36px;height:36px;background:var(--gold);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:.85rem;">
                            {{ strtoupper(substr($review->user->username ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:.875rem;">{{ $review->user->username ?? 'Customer' }}</div>
                            <div style="font-size:.75rem;color:var(--gray);">{{ $review->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                    <div class="stars">
                        @for($i=1;$i<=5;$i++)<i class="fas fa-star" style="{{ $i > $review->rating ? 'color:#ddd' : '' }}"></i>@endfor
                    </div>
                </div>
                <h4 style="font-size:.9rem;margin-bottom:.35rem;">{{ $review->review_title }}</h4>
                <p style="font-size:.875rem;color:var(--gray);line-height:1.7;">{{ $review->description }}</p>
            </div>
            @empty
            <div style="text-align:center;padding:3rem;color:var(--gray);">
                <i class="fas fa-star" style="font-size:2rem;margin-bottom:1rem;display:block;opacity:.3;"></i>
                No reviews yet. Be the first to review!
            </div>
            @endforelse
        </div>

        <div id="tab-write-review" class="tab-content">
            @auth
            <form action="{{ route('reviews.store') }}" method="POST" style="max-width:600px;">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block;font-size:.85rem;font-weight:600;margin-bottom:.75rem;">Your Rating *</label>
                    <div style="display:flex;gap:.5rem;" id="star-rating">
                        @for($i=1;$i<=5;$i++)
                        <label style="cursor:pointer;">
                            <input type="radio" name="rating" value="{{ $i }}" style="display:none;" required>
                            <i class="fas fa-star" style="font-size:1.5rem;color:#ddd;transition:color .15s;" data-val="{{ $i }}"></i>
                        </label>
                        @endfor
                    </div>
                </div>
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block;font-size:.85rem;font-weight:500;margin-bottom:.4rem;">Review Title *</label>
                    <input type="text" name="review_title" class="form-control" placeholder="Summarize your experience" style="padding:.65rem 1rem;border:1.5px solid var(--border);border-radius:8px;font-size:.875rem;width:100%;font-family:var(--font-body);" required>
                </div>
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-size:.85rem;font-weight:500;margin-bottom:.4rem;">Your Review *</label>
                    <textarea name="description" rows="4" style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--border);border-radius:8px;font-size:.875rem;font-family:var(--font-body);resize:vertical;" placeholder="Tell others about your experience…" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit Review</button>
            </form>
            @else
            <div style="padding:2rem;background:var(--warm-white);border-radius:12px;text-align:center;">
                <p style="margin-bottom:1rem;color:var(--gray);">Please sign in to write a review.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Sign In</a>
            </div>
            @endauth
        </div>
    </div>

    <!-- RELATED PRODUCTS -->
    @if($related->isNotEmpty())
    <div style="border-top:1px solid var(--border);padding-top:3rem;">
        <div class="section-header" style="text-align:left;margin-bottom:2rem;">
            <span class="section-label">You may also like</span>
            <h2>Related Products</h2>
        </div>
        <div class="related-grid">
            @foreach($related as $rp)
            <div class="product-card">
                <a href="{{ route('shop.show', $rp->product_id) }}" style="text-decoration:none;">
                    <div class="product-card-img">
                        @if($rp->product_image)<img src="{{ asset('storage/'.$rp->product_image) }}" alt="{{ $rp->product_name }}">
                        @else <i class="fas fa-tshirt"></i> @endif
                    </div>
                </a>
                <div class="product-card-body">
                    <h3 class="product-card-name">{{ $rp->product_name }}</h3>
                    <div class="product-card-price">
                        @if($rp->sizes->isNotEmpty()) From ${{ number_format($rp->sizes->min('pivot.price'),2) }} @else N/A @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
const sizeData = @json($product->sizes->mapWithKeys(fn($s) => [$s->size_id => ['price' => $s->pivot->price, 'stock' => $s->pivot->stock_qty]]));

function selectSize(btn) {
    document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const sizeId = btn.dataset.sizeId;
    const price = btn.dataset.price;
    const stock = parseInt(btn.dataset.stock);
    document.getElementById('selected-size-id').value = sizeId;
    document.getElementById('displayed-price').textContent = '$' + parseFloat(price).toFixed(2);
    document.getElementById('add-cart-btn').disabled = false;
    const qtyInput = document.getElementById('qty-input');
    qtyInput.max = stock;
    if (parseInt(qtyInput.value) > stock) qtyInput.value = stock;
    const stockEl = document.getElementById('stock-info');
    stockEl.style.display = 'block';
    if (stock < 5) { stockEl.textContent = `Only ${stock} left in stock!`; stockEl.className = 'stock-info low'; }
    else { stockEl.textContent = `${stock} in stock`; stockEl.className = 'stock-info'; }
}
function changeQty(delta) {
    const input = document.getElementById('qty-input');
    const val = parseInt(input.value) + delta;
    if (val >= 1 && val <= parseInt(input.max || 99)) input.value = val;
}
function switchTab(name, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}
// Star rating
const stars = document.querySelectorAll('#star-rating label');
stars.forEach((label, idx) => {
    const icon = label.querySelector('i');
    const input = label.querySelector('input');
    label.addEventListener('mouseenter', () => stars.forEach((l, i) => l.querySelector('i').style.color = i <= idx ? '#c9a84c' : '#ddd'));
    label.addEventListener('mouseleave', () => updateStars());
    input.addEventListener('change', () => updateStars());
});
function updateStars() {
    const checked = document.querySelector('#star-rating input:checked');
    if (!checked) { stars.forEach(l => l.querySelector('i').style.color = '#ddd'); return; }
    const val = parseInt(checked.value);
    stars.forEach((l, i) => l.querySelector('i').style.color = i < val ? '#c9a84c' : '#ddd');
}
</script>
@endpush
