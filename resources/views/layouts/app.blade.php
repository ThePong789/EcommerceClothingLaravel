<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LuxeStore') | LuxeStore</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --black: #0a0a0a;
            --cream: #faf8f4;
            --warm-white: #f5f2ed;
            --gold: #c9a84c;
            --gold-light: #e8d5a3;
            --charcoal: #2d2d2d;
            --gray: #888;
            --border: #e8e4de;
            --font-display: 'Playfair Display', serif;
            --font-body: 'DM Sans', sans-serif;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: var(--font-body); background: var(--cream); color: var(--charcoal); }

        /* ── NAVBAR ── */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            background: rgba(250,248,244,.95); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2.5rem; height: 70px;
        }
        .nav-brand {
            font-family: var(--font-display); font-size: 1.6rem; font-weight: 700;
            color: var(--black); text-decoration: none; letter-spacing: -.5px;
        }
        .nav-brand span { color: var(--gold); }
        .nav-links { display: flex; gap: 2rem; list-style: none; }
        .nav-links a {
            text-decoration: none; color: var(--charcoal); font-size: .9rem;
            font-weight: 500; letter-spacing: .5px; text-transform: uppercase;
            transition: color .2s;
        }
        .nav-links a:hover { color: var(--gold); }
        .nav-actions { display: flex; align-items: center; gap: 1.2rem; }
        .nav-icon {
            color: var(--charcoal); font-size: 1.1rem; text-decoration: none;
            position: relative; transition: color .2s;
        }
        .nav-icon:hover { color: var(--gold); }
        .cart-badge {
            position: absolute; top: -8px; right: -8px;
            background: var(--gold); color: #fff; border-radius: 50%;
            font-size: .65rem; width: 18px; height: 18px;
            display: flex; align-items: center; justify-content: center; font-weight: 700;
        }
        .btn-nav {
            background: var(--black); color: #fff; border: none; cursor: pointer;
            padding: .5rem 1.2rem; border-radius: 4px; font-size: .85rem;
            font-family: var(--font-body); text-decoration: none; transition: background .2s;
        }
        .btn-nav:hover { background: var(--gold); }
        .dropdown { position: relative; }
        .dropdown-menu {
            display: none; position: absolute; right: 0; top: calc(100% + 8px);
            background: #fff; border: 1px solid var(--border); border-radius: 8px;
            min-width: 180px; box-shadow: 0 8px 24px rgba(0,0,0,.08);
            overflow: hidden; z-index: 1000;
        }
        .dropdown.open .dropdown-menu { display: block; }
        .dropdown-menu a, .dropdown-menu button {
            display: block; padding: .75rem 1rem; font-size: .85rem; color: var(--charcoal);
            text-decoration: none; background: none; border: none; width: 100%; text-align: left;
            cursor: pointer; font-family: var(--font-body); transition: background .15s;
        }
        .dropdown-menu a:hover, .dropdown-menu button:hover { background: var(--warm-white); }
        .dropdown-menu .divider { height: 1px; background: var(--border); margin: .25rem 0; }

        /* ── FLASH MESSAGES ── */
        .flash { padding: .75rem 1.5rem; margin: 1rem 2.5rem; border-radius: 6px; font-size: .9rem; }
        .flash-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .flash-error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* ── FOOTER ── */
        footer {
            background: var(--black); color: rgba(255,255,255,.7);
            padding: 4rem 2.5rem 2rem;
        }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 3rem; max-width: 1200px; margin: 0 auto; }
        .footer-brand { font-family: var(--font-display); font-size: 1.4rem; color: #fff; margin-bottom: 1rem; }
        .footer-brand span { color: var(--gold); }
        .footer-col h4 { color: #fff; font-size: .85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem; }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: .5rem; }
        .footer-col ul li a { color: rgba(255,255,255,.6); text-decoration: none; font-size: .85rem; transition: color .2s; }
        .footer-col ul li a:hover { color: var(--gold); }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,.1); margin-top: 3rem; padding-top: 1.5rem; text-align: center; font-size: .8rem; color: rgba(255,255,255,.4); max-width: 1200px; margin-left: auto; margin-right: auto; }
        .social-links { display: flex; gap: .8rem; margin-top: 1rem; }
        .social-links a { color: rgba(255,255,255,.5); font-size: 1.1rem; transition: color .2s; }
        .social-links a:hover { color: var(--gold); }

        /* ── COMMON BUTTONS ── */
        .btn { display: inline-flex; align-items: center; gap: .5rem; padding: .75rem 1.75rem; border-radius: 4px; font-size: .9rem; font-weight: 500; cursor: pointer; border: none; text-decoration: none; transition: all .2s; font-family: var(--font-body); }
        .btn-primary { background: var(--black); color: #fff; }
        .btn-primary:hover { background: var(--charcoal); transform: translateY(-1px); }
        .btn-gold { background: var(--gold); color: #fff; }
        .btn-gold:hover { background: #b8923f; }
        .btn-outline { background: transparent; color: var(--black); border: 1.5px solid var(--black); }
        .btn-outline:hover { background: var(--black); color: #fff; }
        .btn-sm { padding: .45rem 1rem; font-size: .8rem; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-danger:hover { background: #c82333; }

        /* ── CONTAINER ── */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
        .section-header { text-align: center; margin-bottom: 3rem; }
        .section-header h2 { font-family: var(--font-display); font-size: 2.4rem; color: var(--black); margin-bottom: .75rem; }
        .section-header p { color: var(--gray); font-size: 1rem; }
        .section-label { font-size: .75rem; text-transform: uppercase; letter-spacing: 2px; color: var(--gold); font-weight: 600; display: block; margin-bottom: .5rem; }

        /* ── PRODUCT CARD ── */
        .product-card {
            background: #fff; border-radius: 12px; overflow: hidden;
            transition: transform .3s, box-shadow .3s; border: 1px solid var(--border);
        }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,.1); }
        .product-card-img { width: 100%; height: 260px; object-fit: cover; background: var(--warm-white); display: flex; align-items: center; justify-content: center; color: var(--gray); font-size: 3rem; }
        .product-card-img img { width: 100%; height: 100%; object-fit: cover; }
        .product-card-body { padding: 1.25rem; }
        .product-card-cat { font-size: .75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--gold); font-weight: 600; }
        .product-card-name { font-family: var(--font-display); font-size: 1.1rem; margin: .3rem 0 .5rem; color: var(--black); }
        .product-card-price { font-size: 1.05rem; font-weight: 600; color: var(--charcoal); }
        .product-card-price .original { text-decoration: line-through; color: var(--gray); font-weight: 400; font-size: .9rem; margin-right: .4rem; }
        .stars { color: var(--gold); font-size: .85rem; }
        .stars .empty { color: #ddd; }

        /* ── BADGE ── */
        .badge { display: inline-block; padding: .25rem .6rem; border-radius: 50px; font-size: .72rem; font-weight: 600; }
        .badge-gold { background: var(--gold-light); color: #8a6d20; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger  { background: #f8d7da; color: #721c24; }
        .badge-info    { background: #d1ecf1; color: #0c5460; }
        .badge-secondary { background: #e2e3e5; color: #383d41; }

        /* ── PAGE HERO ── */
        .page-hero { background: var(--black); color: #fff; padding: 4rem 2.5rem; text-align: center; }
        .page-hero h1 { font-family: var(--font-display); font-size: 3rem; margin-bottom: .75rem; }
        .page-hero p { color: rgba(255,255,255,.6); font-size: 1rem; }
        .breadcrumb { display: flex; justify-content: center; gap: .5rem; font-size: .85rem; color: rgba(255,255,255,.5); margin-top: .75rem; }
        .breadcrumb a { color: var(--gold); text-decoration: none; }

        .hamburger { display: none; background: none; border: none; cursor: pointer; padding: .4rem; color: var(--charcoal); font-size: 1.25rem; }
        .mobile-nav { display: none; background: #fff; border-top: 1px solid var(--border); position: fixed; top: 70px; left: 0; right: 0; z-index: 999; box-shadow: 0 4px 12px rgba(0,0,0,.08); }
        .mobile-nav ul { list-style: none; padding: .5rem 1.25rem 1rem; }
        .mobile-nav ul li a { display: block; padding: .75rem 0; font-size: .95rem; color: var(--charcoal); text-decoration: none; border-bottom: 1px solid #f5f5f5; font-family: var(--font-body); }
        .mobile-nav ul li:last-child a { border-bottom: none; }
        .mobile-nav ul li a:hover { color: var(--gold); }
        .mobile-nav.open { display: block; }
        @media (max-width: 768px) {
            .navbar { padding: 0 1.25rem; }
            .nav-links { display: none; }
            .hamburger { display: block; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 2rem; }
        }
    </style>
    @stack('styles')
</head>
<body style="padding-top: 70px;">

<nav class="navbar">
    <a href="{{ route('home') }}" class="nav-brand">Luxe<span>Store</span></a>
    <ul class="nav-links">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('shop') }}">Shop</a></li>
        <li><a href="{{ route('blog') }}">Blog</a></li>
        <li><a href="{{ route('about') }}">About</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>
    </ul>
    <div class="nav-actions">
        <button class="hamburger" onclick="toggleMobileNav()" aria-label="Menu"><i class="fas fa-bars" id="hamburger-icon"></i></button>
        <a href="{{ route('shop') }}?search=" class="nav-icon" title="Search"><i class="fas fa-search"></i></a>
        @auth
            <a href="{{ route('cart') }}" class="nav-icon" title="Cart">
                <i class="fas fa-shopping-bag"></i>
                @php try { $cartCount = auth()->user()->cart ? auth()->user()->cart->items->count() : 0; } catch (\Exception $e) { $cartCount = 0; } @endphp
                @if($cartCount > 0)<span class="cart-badge">{{ $cartCount }}</span>@endif
            </a>
            <div class="dropdown" id="user-dropdown">
                <a href="#" class="nav-icon" onclick="toggleDropdown(event)"><i class="fas fa-user"></i></a>
                <div class="dropdown-menu">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt fa-fw"></i> Admin Panel</a>
                    @elseif(auth()->user()->isStaff())
                        <a href="{{ route('staff.dashboard') }}"><i class="fas fa-tasks fa-fw"></i> Staff Panel</a>
                    @endif
                    <a href="{{ route('profile') }}"><i class="fas fa-user-circle fa-fw"></i> Profile</a>
                    <a href="{{ route('orders.index') }}"><i class="fas fa-box fa-fw"></i> My Orders</a>
                    <div class="divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"><i class="fas fa-sign-out-alt fa-fw"></i> Logout</button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn-nav">Login</a>
            <a href="{{ route('register') }}" class="btn btn-gold btn-sm">Register</a>
        @endauth
    </div>
</nav>

<div class="mobile-nav" id="mobile-nav">
    <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('shop') }}">Shop</a></li>
        <li><a href="{{ route('blog') }}">Blog</a></li>
        <li><a href="{{ route('about') }}">About</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>
    </ul>
</div>

@if(session('success'))
    <div class="flash flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="flash flash-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
@endif

@yield('content')

<footer>
    <div class="footer-grid">
        <div>
            <div class="footer-brand">Luxe<span>Store</span></div>
            <p style="font-size:.85rem;line-height:1.7;margin-bottom:1rem;">Curated fashion & lifestyle products for the modern individual. Quality meets elegance.</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-pinterest"></i></a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Shop</h4>
            <ul>
                <li><a href="{{ route('shop') }}">All Products</a></li>
                <li><a href="{{ route('shop') }}?sort=newest">New Arrivals</a></li>
                <li><a href="{{ route('shop') }}?is_featured=1">Featured</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Info</h4>
            <ul>
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="{{ route('blog') }}">Blog</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Account</h4>
            <ul>
                @auth
                    <li><a href="{{ route('profile') }}">My Profile</a></li>
                    <li><a href="{{ route('orders.index') }}">My Orders</a></li>
                    <li><a href="{{ route('cart') }}">My Cart</a></li>
                @else
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; {{ date('Y') }} LuxeStore. All rights reserved.
    </div>
</footer>

@stack('scripts')
<script>
function toggleDropdown(e) {
    e.preventDefault();
    document.getElementById('user-dropdown').classList.toggle('open');
}
document.addEventListener('click', function(e) {
    const dd = document.getElementById('user-dropdown');
    if (dd && !dd.contains(e.target)) dd.classList.remove('open');
});
function toggleMobileNav() {
    const nav = document.getElementById('mobile-nav');
    const icon = document.getElementById('hamburger-icon');
    nav.classList.toggle('open');
    icon.className = nav.classList.contains('open') ? 'fas fa-times' : 'fas fa-bars';
}
</script>
</body>
</html>