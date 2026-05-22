<?php $__env->startSection('title', 'About Us'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .about-hero { background: var(--black); color: #fff; padding: 6rem 2.5rem; text-align: center; }
    .about-hero h1 { font-family: var(--font-display); font-size: 3.5rem; margin-bottom: 1rem; }
    .about-hero p { color: rgba(255,255,255,.65); max-width: 560px; margin: 0 auto; line-height: 1.8; }
    .about-section { padding: 5rem 0; }
    .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }
    .about-img { width: 100%; aspect-ratio: 4/3; background: var(--warm-white); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 6rem; color: var(--gold); }
    .about-content h2 { font-family: var(--font-display); font-size: 2.5rem; margin-bottom: 1.25rem; }
    .about-content p { color: var(--gray); line-height: 1.8; margin-bottom: 1rem; }
    .values-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 2rem; }
    .value-card { text-align: center; padding: 2.5rem 1.5rem; background: #fff; border-radius: 16px; border: 1px solid var(--border); }
    .value-icon { width: 64px; height: 64px; background: var(--warm-white); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; font-size: 1.5rem; color: var(--gold); }
    .value-card h3 { font-family: var(--font-display); font-size: 1.1rem; margin-bottom: .5rem; }
    .value-card p { font-size: .875rem; color: var(--gray); line-height: 1.6; }
    .stats-row { background: var(--black); padding: 4rem 2.5rem; }
    .stats-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 2rem; max-width: 900px; margin: 0 auto; text-align: center; }
    .stat-num { font-family: var(--font-display); font-size: 2.5rem; color: var(--gold); font-weight: 700; }
    .stat-lbl { color: rgba(255,255,255,.6); font-size: .85rem; margin-top: .25rem; }
    @media(max-width:768px) { .about-grid,.values-grid,.stats-grid { grid-template-columns: 1fr; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="about-hero">
    <span class="section-label" style="color:var(--gold-light);">Our Story</span>
    <h1>Crafted for the <br>Modern Individual</h1>
    <p>We believe that great style shouldn't come at the cost of sustainability or ethics. LuxeStore was founded with a simple mission: offer premium quality products that you can feel good about.</p>
</div>

<section class="about-section">
    <div class="container">
        <div class="about-grid">
            <div class="about-img" style="background:url( <?php echo e(asset('storage/aboutUs.webp')); ?> );background-position:center;background-size:cover;background-repeat:no-repeat;width:100%;"></div>
            
            <div class="about-content">
                <span class="section-label">Who We Are</span>
                <h2>Redefining Luxury Fashion</h2>
                <p>Founded in 2020, LuxeStore has grown from a small boutique into a destination for discerning shoppers who demand both quality and style. We curate every product in our collection with careful attention to craftsmanship, materials, and ethical production.</p>
                <p>Our team of fashion experts travels the globe sourcing exclusive pieces and working with artisans to bring you collections that are as unique as you are.</p>
                <a href="<?php echo e(route('shop')); ?>" class="btn btn-primary" style="margin-top:.5rem;">Explore Collection</a>
            </div>
        </div>
    </div>
</section>

<div class="stats-row">
    <div class="stats-grid">
        <div><div class="stat-num">50K+</div><div class="stat-lbl">Happy Customers</div></div>
        <div><div class="stat-num">2,000+</div><div class="stat-lbl">Products</div></div>
        <div><div class="stat-num">98%</div><div class="stat-lbl">Satisfaction Rate</div></div>
        <div><div class="stat-num">4.9★</div><div class="stat-lbl">Average Rating</div></div>
    </div>
</div>

<section class="about-section" style="background:var(--warm-white);">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Our Values</span>
            <h2>What We Stand For</h2>
        </div>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-leaf"></i></div>
                <h3>Sustainability</h3>
                <p>We're committed to reducing our environmental footprint through responsible sourcing and eco-friendly packaging.</p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-award"></i></div>
                <h3>Quality First</h3>
                <p>Every product in our store meets our rigorous quality standards. We only offer what we'd be proud to wear ourselves.</p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-heart"></i></div>
                <h3>Customer Love</h3>
                <p>Our customers are at the heart of everything we do. Your satisfaction is our highest priority, always.</p>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\vesot\Downloads\Telegram Desktop\LRV\Laravel\resources\views/home/about.blade.php ENDPATH**/ ?>