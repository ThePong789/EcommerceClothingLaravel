<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ── HERO ── */
    .hero {
        min-height: 90vh; display: grid; grid-template-columns: 1fr 1fr;
        background: var(--black); overflow: hidden;
    }
    .hero-content {
        display: flex; flex-direction: column; justify-content: center;
        padding: 6rem 4rem 6rem 5rem; color: #fff;
    }
    .hero-label { font-size: .75rem; text-transform: uppercase; letter-spacing: 3px; color: var(--gold); margin-bottom: 1.5rem; font-weight: 600; }
    .hero-title { font-family: var(--font-display); font-size: clamp(2.5rem, 5vw, 4rem); line-height: 1.1; margin-bottom: 1.5rem; }
    .hero-title span { color: var(--gold); }
    .hero-desc { color: rgba(255,255,255,.65); line-height: 1.8; margin-bottom: 2.5rem; max-width: 440px; font-size: 1rem; }
    .hero-cta { display: flex; gap: 1rem; flex-wrap: wrap; }
    .hero-image {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;
    }
    .hero-image::before {
        content: ''; position: absolute; width: 400px; height: 400px;
        border: 1px solid rgba(201,168,76,.2); border-radius: 50%;
        top: 50%; left: 50%; transform: translate(-50%, -50%);
        animation: pulse 3s ease-in-out infinite;
    }
    .hero-image::after {
        content: ''; position: absolute; width: 250px; height: 250px;
        border: 1px solid rgba(201,168,76,.15); border-radius: 50%;
        top: 50%; left: 50%; transform: translate(-50%, -50%);
    }
    .hero-icon { font-size: 8rem; color: var(--gold); opacity: .15; z-index: 1; }
    @keyframes pulse { 0%,100%{transform:translate(-50%,-50%) scale(1);opacity:1} 50%{transform:translate(-50%,-50%) scale(1.05);opacity:.7} }

    /* ── CATEGORIES ── */
    .categories-section { padding: 5rem 0; background: var(--warm-white); }
    .cat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    .cat-card {
        background: #fff; border-radius: 16px; overflow: hidden; text-decoration: none;
        display: flex; align-items: center; gap: 1rem; padding: 1.5rem;
        border: 1px solid var(--border); transition: all .3s;
    }
    .cat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(0,0,0,.08); border-color: var(--gold-light); }
    .cat-icon { width: 60px; height: 60px; background: var(--warm-white); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0; }
    .cat-name { font-family: var(--font-display); font-size: 1.1rem; color: var(--black); }
    .cat-count { font-size: .8rem; color: var(--gray); margin-top: .15rem; }

    /* ── PRODUCTS ── */
    .products-section { padding: 5rem 0; }
    .products-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }

    /* ── BANNER ── */
    .banner-section {
        background: var(--black); padding: 5rem 2.5rem; text-align: center; color: #fff;
        margin: 0; position: relative; overflow: hidden;
    }
    .banner-section::before { content: ''; position: absolute; inset: 0; background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c9a84c' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
    .banner-section h2 { font-family: var(--font-display); font-size: 3rem; margin-bottom: 1rem; position: relative; }
    .banner-section p { color: rgba(255,255,255,.65); margin-bottom: 2rem; position: relative; }

    /* ── BLOG ── */
    .blog-section { padding: 5rem 0; background: var(--warm-white); }
    .blog-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; }
    .blog-card { background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid var(--border); text-decoration: none; display: block; transition: all .3s; }
    .blog-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,.1); }
    .blog-card-img { height: 200px; background: linear-gradient(135deg, var(--black), #2d2d4e); display: flex; align-items: center; justify-content: center; color: var(--gold); font-size: 3rem; overflow: hidden; }
    .blog-card-img img { width: 100%; height: 100%; object-fit: cover; }
    .blog-card-body { padding: 1.5rem; }
    .blog-card-date { font-size: .75rem; color: var(--gray); margin-bottom: .5rem; }
    .blog-card-title { font-family: var(--font-display); font-size: 1.15rem; color: var(--black); margin-bottom: .5rem; line-height: 1.4; }
    .blog-card-subtitle { font-size: .875rem; color: var(--gray); line-height: 1.6; }

    /* ── REVIEWS ── */
    .reviews-section { padding: 5rem 0; }
    .reviews-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    .review-card { background: #fff; border-radius: 16px; padding: 2rem; border: 1px solid var(--border); }
    .review-stars { color: var(--gold); margin-bottom: 1rem; }
    .review-text { font-size: .9rem; color: var(--charcoal); line-height: 1.7; margin-bottom: 1.25rem; font-style: italic; }
    .review-author { display: flex; align-items: center; gap: .75rem; }
    .review-avatar { width: 40px; height: 40px; background: var(--gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: .9rem; }
    .review-name { font-weight: 600; font-size: .9rem; }
    .review-role { font-size: .75rem; color: var(--gray); }

    @media(max-width:1024px) { .products-grid { grid-template-columns: repeat(1,1fr); } }
    @media(max-width:768px) { .hero { grid-template-columns: 1fr; } .hero-image { min-height: 250px; } .cat-grid,.blog-grid,.reviews-grid { grid-template-columns: 1fr; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <span class="hero-label">New Collection 2025</span>
        <h1 class="hero-title">Elevate Your <span>Style</span> With Luxury</h1>
        <p class="hero-desc">Discover our curated collection of premium fashion and lifestyle products. Crafted for those who demand excellence in every detail.</p>
        <div class="hero-cta">
            <a href="<?php echo e(route('shop')); ?>" class="btn btn-gold" style="font-size:1rem;padding:.9rem 2.5rem;">Shop Now <i class="fas fa-arrow-right"></i></a>
            <a href="<?php echo e(route('about')); ?>" class="btn btn-outline" style="color:#fff;border-color:rgba(255,255,255,.3);font-size:1rem;padding:.9rem 2rem;">Our Story</a>
        </div>
    </div>
    <div style="background-image: url( <?php echo e(asset('storage/products/banner.jpeg')); ?>); background-repeat: no-repeat; background-position: center; background-size: cover;">
        
    </div>
</section>

<!-- CATEGORIES -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Browse</span>
            <h2>Shop by Category</h2>
            <p>Find exactly what you're looking for</p>
        </div>
        <div class="cat-grid">
            <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('shop', ['category' => $cat->slug])); ?>" class="cat-card">
                <div class="cat-icon">
                    <?php if($cat->category_image): ?>
                        <img src="<?php echo e(asset('storage/'.$cat->category_image)); ?>" alt="<?php echo e($cat->category_name); ?>" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                    <?php else: ?>
                        <i class="fas fa-tag" style="color:var(--gold)"></i>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="cat-name"><?php echo e($cat->category_name); ?></div>
                    <div class="cat-count"><?php echo e($cat->products_count); ?> products</div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="grid-column:1/-1;text-align:center;color:var(--gray);padding:2rem;">No categories yet.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- FEATURED PRODUCTS -->
<section class="products-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Featured</span>
            <h2>Our Best Sellers</h2>
            <p>Handpicked for quality and style</p>
        </div>
        <div class="products-grid">
            <?php $__empty_1 = true; $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="product-card">
                <a href="<?php echo e(route('shop.show', $product->product_id)); ?>" style="text-decoration:none;">
                    <div class="product-card-img">
                        <?php if($product->product_image): ?>
                            <img src="<?php echo e(asset('storage/'.$product->product_image)); ?>" alt="<?php echo e($product->product_name); ?>">
                        <?php else: ?>
                            <i class="fas fa-tshirt"></i>
                        <?php endif; ?>
                    </div>
                </a>
                <div class="product-card-body">
                    <div class="product-card-cat"><?php echo e($product->category->category_name ?? ''); ?></div>
                    <a href="<?php echo e(route('shop.show', $product->product_id)); ?>" style="text-decoration:none;">
                        <h3 class="product-card-name"><?php echo e($product->product_name); ?></h3>
                    </a>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-top:.5rem;">
                        <div class="product-card-price">
                            <?php if($product->sizes->isNotEmpty()): ?>
                                From $<?php echo e(number_format($product->sizes->min('pivot.price'), 2)); ?>

                            <?php else: ?> N/A <?php endif; ?>
                        </div>
                        <div class="stars">
                            <?php $r = round($product->reviews->avg('rating') ?? 0); ?>
                            <?php for($i=1;$i<=5;$i++): ?><i class="fas fa-star<?php echo e($i > $r ? ' empty' : ''); ?>" style="<?php echo e($i > $r ? 'color:#ddd' : ''); ?>"></i><?php endfor; ?>
                        </div>
                    </div>
                    <a href="<?php echo e(route('shop.show', $product->product_id)); ?>" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:.75rem;">View Product</a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="grid-column:1/-1;text-align:center;color:var(--gray);padding:3rem;">
                <i class="fas fa-box-open" style="font-size:3rem;margin-bottom:1rem;display:block;opacity:.3;"></i>
                No featured products yet.
            </div>
            <?php endif; ?>
        </div>
        <div style="text-align:center;margin-top:3rem;">
            <a href="<?php echo e(route('shop')); ?>" class="btn btn-primary" style="padding:.9rem 2.5rem;">View All Products <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- BANNER -->
<section class="banner-section">
    <span class="section-label" style="color:var(--gold-light)">Limited Time</span>
    <h2>Free Shipping on Orders Over $100</h2>
    <p>Shop more, save more. Enjoy complimentary worldwide shipping on qualifying orders.</p>
    <a href="<?php echo e(route('shop')); ?>" class="btn btn-gold" style="font-size:1rem;padding:.9rem 2.5rem;">Shop Now</a>
</section>

<!-- BLOG -->
<?php if($latestBlogs->isNotEmpty()): ?>
<section class="blog-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Stories</span>
            <h2>From Our Blog</h2>
            <p>Style tips, trends and inspiration</p>
        </div>
        <div class="blog-grid">
            <?php $__currentLoopData = $latestBlogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('blog.show', $blog->blog_detail_id)); ?>" class="blog-card">
                <div class="blog-card-img">
                    <?php if($blog->blog_image): ?>
                        <img src="<?php echo e(asset('storage/'.$blog->blog_image)); ?>" alt="<?php echo e($blog->title); ?>">
                    <?php else: ?>
                        <i class="fas fa-newspaper"></i>
                    <?php endif; ?>
                </div>
                <div class="blog-card-body">
                    <div class="blog-card-date"><?php echo e($blog->created_at->format('M d, Y')); ?> · <?php echo e($blog->blog->author_name ?? ''); ?></div>
                    <h3 class="blog-card-title"><?php echo e($blog->title); ?></h3>
                    <p class="blog-card-subtitle"><?php echo e(Str::limit($blog->subtitle, 80)); ?></p>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div style="text-align:center;margin-top:3rem;">
            <a href="<?php echo e(route('blog')); ?>" class="btn btn-outline">Read All Posts <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- REVIEWS -->
<?php if($reviews->isNotEmpty()): ?>
<section class="reviews-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Testimonials</span>
            <h2>What Our Customers Say</h2>
        </div>
        <div class="reviews-grid">
            <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="review-card">
                <div class="review-stars">
                    <?php for($i=1;$i<=5;$i++): ?><i class="fas fa-star" style="<?php echo e($i > $review->rating ? 'color:#ddd' : ''); ?>"></i><?php endfor; ?>
                </div>
                <p class="review-text">"<?php echo e(Str::limit($review->description, 120)); ?>"</p>
                <div class="review-author">
                    <div class="review-avatar"><?php echo e(strtoupper(substr($review->user->username ?? 'U', 0, 1))); ?></div>
                    <div>
                        <div class="review-name"><?php echo e($review->user->username ?? 'Customer'); ?></div>
                        <div class="review-role"><?php echo e($review->review_title); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\vesot\Downloads\Telegram Desktop\LRV\Laravel\resources\views/home/index.blade.php ENDPATH**/ ?>