<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | LuxeStore Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --sidebar-w: 260px;
            --primary: #1a1a2e;
            --primary-light: #16213e;
            --accent: #c9a84c;
            --accent-light: rgba(201,168,76,.15);
            --bg: #f4f5f7;
            --white: #ffffff;
            --text: #2d2d2d;
            --text-muted: #888;
            --border: #e5e7eb;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #3b82f6;
            --font: 'Inter', sans-serif;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: var(--font); background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w); background: var(--primary); color: rgba(255,255,255,.75);
            display: flex; flex-direction: column; position: fixed; top: 0; left: 0; height: 100vh;
            overflow-y: auto; z-index: 100;
        }
        .sidebar-brand {
            padding: 1.75rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,.08);
            font-size: 1.25rem; font-weight: 700; color: #fff; letter-spacing: -.3px;
        }
        .sidebar-brand span { color: var(--accent); }
        .sidebar-label { font-size: .65rem; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,.3); padding: 1.25rem 1.5rem .5rem; font-weight: 600; }
        .sidebar-nav { list-style: none; padding: 0 .75rem; flex: 1; }
        .sidebar-nav li a {
            display: flex; align-items: center; gap: .75rem; padding: .65rem .75rem;
            color: rgba(255,255,255,.65); text-decoration: none; border-radius: 8px;
            font-size: .875rem; font-weight: 500; transition: all .15s; margin-bottom: 2px;
        }
        .sidebar-nav li a:hover, .sidebar-nav li a.active {
            background: rgba(255,255,255,.08); color: #fff;
        }
        .sidebar-nav li a.active { background: var(--accent-light); color: var(--accent); }
        .sidebar-nav li a i { width: 18px; text-align: center; font-size: .9rem; }
        .sidebar-footer {
            padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,.08);
            font-size: .8rem; color: rgba(255,255,255,.4);
        }
        .sidebar-footer .user-info { display: flex; align-items: center; gap: .6rem; }
        .sidebar-footer .avatar { width: 32px; height: 32px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .85rem; color: #fff; font-weight: 600; }

        /* MAIN */
        .main-wrap { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }

        /* TOPBAR */
        .topbar {
            background: var(--white); border-bottom: 1px solid var(--border);
            padding: 0 2rem; height: 64px; display: flex; align-items: center;
            justify-content: space-between; position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-size: 1rem; font-weight: 600; }
        .topbar-breadcrumb { font-size: .8rem; color: var(--text-muted); margin-top: 2px; }
        .topbar-actions { display: flex; align-items: center; gap: 1rem; }
        .topbar-actions a { color: var(--text-muted); font-size: .85rem; text-decoration: none; display: flex; align-items: center; gap: .4rem; }
        .topbar-actions a:hover { color: var(--text); }

        /* PAGE CONTENT */
        .page-content { padding: 2rem; flex: 1; }

        /* FLASH */
        .alert { padding: .85rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: .875rem; display: flex; align-items: center; gap: .6rem; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-danger   { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* CARDS */
        .card { background: var(--white); border-radius: 12px; border: 1px solid var(--border); overflow: hidden; }
        .card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .card-header h3 { font-size: 1rem; font-weight: 600; }
        .card-body { padding: 1.5rem; }

        /* STAT CARDS */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 2rem; }
        .stat-card { background: var(--white); border-radius: 12px; border: 1px solid var(--border); padding: 1.5rem; display: flex; align-items: flex-start; gap: 1rem; }
        .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
        .stat-icon.gold { background: var(--accent-light); color: var(--accent); }
        .stat-icon.blue { background: rgba(59,130,246,.1); color: var(--info); }
        .stat-icon.green { background: rgba(16,185,129,.1); color: var(--success); }
        .stat-icon.red { background: rgba(239,68,68,.1); color: var(--danger); }
        .stat-icon.purple { background: rgba(139,92,246,.1); color: #8b5cf6; }
        .stat-icon.orange { background: rgba(245,158,11,.1); color: var(--warning); }
        .stat-val { font-size: 1.75rem; font-weight: 700; line-height: 1; }
        .stat-label { font-size: .8rem; color: var(--text-muted); margin-top: .25rem; }

        /* TABLE */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        thead th { background: #f9fafb; padding: .75rem 1rem; text-align: left; font-weight: 600; font-size: .75rem; text-transform: uppercase; letter-spacing: .5px; color: var(--text-muted); border-bottom: 1px solid var(--border); }
        tbody td { padding: .85rem 1rem; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #fafbfc; }

        /* FORMS */
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: .85rem; font-weight: 500; margin-bottom: .4rem; }
        .form-control {
            width: 100%; padding: .6rem .9rem; border: 1.5px solid var(--border); border-radius: 8px;
            font-size: .875rem; font-family: var(--font); color: var(--text); background: #fff;
            transition: border-color .2s;
        }
        .form-control:focus { outline: none; border-color: var(--accent); }
        select.form-control { cursor: pointer; }
        textarea.form-control { min-height: 100px; resize: vertical; }

        /* BUTTONS */
        .btn { display: inline-flex; align-items: center; gap: .4rem; padding: .55rem 1.1rem; border-radius: 8px; font-size: .85rem; font-weight: 500; cursor: pointer; border: none; text-decoration: none; transition: all .15s; font-family: var(--font); }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: #2d2d4e; }
        .btn-accent  { background: var(--accent); color: #fff; }
        .btn-accent:hover { background: #b8923f; }
        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { background: #059669; }
        .btn-danger  { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-outline { background: transparent; color: var(--text); border: 1.5px solid var(--border); }
        .btn-outline:hover { border-color: var(--text); }
        .btn-sm { padding: .35rem .7rem; font-size: .78rem; }
        .btn-xs { padding: .22rem .55rem; font-size: .72rem; }

        /* BADGE */
        .badge { padding: .25rem .65rem; border-radius: 50px; font-size: .72rem; font-weight: 600; }
        .badge-success  { background: #d1fae5; color: #065f46; }
        .badge-warning  { background: #fef3c7; color: #92400e; }
        .badge-danger   { background: #fee2e2; color: #991b1b; }
        .badge-info     { background: #dbeafe; color: #1e40af; }
        .badge-secondary{ background: #f3f4f6; color: #6b7280; }

        /* PAGINATION */
        .pagination { display: flex; gap: .3rem; list-style: none; margin-top: 1.5rem; }
        .pagination .page-item .page-link { padding: .45rem .8rem; border-radius: 6px; font-size: .85rem; text-decoration: none; color: var(--text); border: 1px solid var(--border); }
        .pagination .page-item.active .page-link { background: var(--primary); color: #fff; border-color: var(--primary); }

        /* GRID */
        .row { display: grid; gap: 1.5rem; }
        .col-2 { grid-template-columns: 1fr 1fr; }
        .col-3 { grid-template-columns: 1fr 1fr 1fr; }
        .col-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }

        /* EMPTY STATE */
        .empty-state { text-align: center; padding: 4rem 2rem; color: var(--text-muted); }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: .3; }
        .empty-state h3 { font-size: 1.1rem; margin-bottom: .5rem; color: var(--text); }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">Luxe<span>Store</span></div>

    @if(auth()->user()->isAdmin())
    <span class="sidebar-label">Main</span>
    <ul class="sidebar-nav">
        <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    </ul>
    <span class="sidebar-label">Catalog</span>
    <ul class="sidebar-nav">
        <li><a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}"><i class="fas fa-box"></i> Products</a></li>
        <li><a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories*') ? 'active' : '' }}"><i class="fas fa-tags"></i> Categories</a></li>
    </ul>
    <span class="sidebar-label">Commerce</span>
    <ul class="sidebar-nav">
        <li><a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}"><i class="fas fa-shopping-cart"></i> Orders</a></li>
        <li><a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews*') ? 'active' : '' }}"><i class="fas fa-star"></i> Reviews</a></li>
    </ul>
    <span class="sidebar-label">Content</span>
    <ul class="sidebar-nav">
        <li><a href="{{ route('admin.blog.index') }}" class="{{ request()->routeIs('admin.blog*') ? 'active' : '' }}"><i class="fas fa-newspaper"></i> Blog</a></li>
    </ul>
    <span class="sidebar-label">Users</span>
    <ul class="sidebar-nav">
        <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}"><i class="fas fa-users"></i> Users</a></li>
    </ul>
    @else
    <span class="sidebar-label">Main</span>
    <ul class="sidebar-nav">
        <li><a href="{{ route('staff.dashboard') }}" class="{{ request()->routeIs('staff.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    </ul>
    <span class="sidebar-label">Catalog</span>
    <ul class="sidebar-nav">
        <li><a href="{{ route('staff.products.index') }}" class="{{ request()->routeIs('staff.products*') ? 'active' : '' }}"><i class="fas fa-box"></i> Products</a></li>
        <li><a href="{{ route('staff.categories.index') }}" class="{{ request()->routeIs('staff.categories*') ? 'active' : '' }}"><i class="fas fa-tags"></i> Categories</a></li>
    </ul>
    <span class="sidebar-label">Commerce</span>
    <ul class="sidebar-nav">
        <li><a href="{{ route('staff.orders.index') }}" class="{{ request()->routeIs('staff.orders*') ? 'active' : '' }}"><i class="fas fa-shopping-cart"></i> Orders</a></li>
        <li><a href="{{ route('staff.reviews.index') }}" class="{{ request()->routeIs('staff.reviews*') ? 'active' : '' }}"><i class="fas fa-star"></i> Reviews</a></li>
    </ul>
    @endif

    <ul class="sidebar-nav" style="margin-top:auto;">
        <li><a href="{{ route('home') }}"><i class="fas fa-globe"></i> View Store</a></li>
    </ul>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</div>
            <div>
                <div style="color:#fff;font-weight:500;font-size:.8rem;">{{ auth()->user()->username }}</div>
                <div>{{ auth()->user()->role->role_name ?? '' }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin-top:.75rem;">
            @csrf
            <button style="background:none;border:none;color:rgba(255,255,255,.4);cursor:pointer;font-size:.8rem;font-family:var(--font);" type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
    </div>
</aside>

<div class="main-wrap">
    <header class="topbar">
        <div>
            <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
            <div class="topbar-breadcrumb">@yield('breadcrumb', 'Home')</div>
        </div>
        <div class="topbar-actions">
            <a href="{{ route('home') }}"><i class="fas fa-globe"></i> Storefront</a>
        </div>
    </header>

    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
