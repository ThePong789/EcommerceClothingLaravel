@extends('layouts.app')

@section('title', 'My Profile')

@push('styles')
<style>
    .profile-layout { display: grid; grid-template-columns: 260px 1fr; gap: 2rem; padding: 3rem 0; }
    .profile-sidebar { position: sticky; top: 90px; align-self: start; }
    .profile-nav { background: #fff; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
    .profile-nav-item { display: flex; align-items: center; gap: .75rem; padding: .9rem 1.25rem; text-decoration: none; color: var(--charcoal); font-size: .875rem; font-weight: 500; border-bottom: 1px solid var(--border); transition: all .15s; }
    .profile-nav-item:last-child { border-bottom: none; }
    .profile-nav-item:hover, .profile-nav-item.active { background: var(--warm-white); color: var(--black); }
    .profile-nav-item i { width: 18px; text-align: center; color: var(--gold); }
    .profile-section { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; margin-bottom: 1.5rem; }
    .profile-section h3 { font-family: var(--font-display); font-size: 1.1rem; margin-bottom: 1.25rem; padding-bottom: .75rem; border-bottom: 1px solid var(--border); }
    .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-g { margin-bottom: 1.1rem; }
    .form-g label { display: block; font-size: .85rem; font-weight: 500; margin-bottom: .4rem; }
    .form-g input { width: 100%; padding: .65rem 1rem; border: 1.5px solid var(--border); border-radius: 8px; font-size: .875rem; font-family: var(--font-body); }
    .form-g input:focus { outline: none; border-color: var(--gold); }
    .address-item { border: 1px solid var(--border); border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: .75rem; display: flex; align-items: flex-start; justify-content: space-between; }
    .user-avatar { width: 80px; height: 80px; background: var(--gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; color: #fff; margin: 0 auto 1rem; }
    .user-name { text-align: center; font-family: var(--font-display); font-size: 1.25rem; margin-bottom: .25rem; }
    .user-role { text-align: center; font-size: .8rem; color: var(--gray); }
    @media(max-width:768px) { .profile-layout { grid-template-columns: 1fr; } .profile-sidebar { position: static; } }
</style>
@endpush

@section('content')
<div class="page-hero">
    <h1>My Profile</h1>
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> <span>/</span> <span>Profile</span></div>
</div>

<div class="container">
    <div class="profile-layout">
        <!-- SIDEBAR -->
        <div class="profile-sidebar">
            <div style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:1.5rem;margin-bottom:1rem;text-align:center;">
                <div class="user-avatar">{{ strtoupper(substr($user->username, 0, 1)) }}</div>
                <div class="user-name">{{ $user->first_name ?? $user->username }}</div>
                <div class="user-role">{{ $user->role->role_name ?? 'Customer' }}</div>
                <div style="font-size:.8rem;color:var(--gray);margin-top:.25rem;">{{ $user->email }}</div>
            </div>
            <div class="profile-nav">
                <a href="{{ route('profile') }}" class="profile-nav-item active"><i class="fas fa-user"></i> Profile Info</a>
                <a href="{{ route('orders.index') }}" class="profile-nav-item"><i class="fas fa-box"></i> My Orders</a>
                <a href="{{ route('cart') }}" class="profile-nav-item"><i class="fas fa-shopping-bag"></i> My Cart</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="profile-nav-item" style="width:100%;background:none;border:none;cursor:pointer;font-family:var(--font-body);text-align:left;">
                        <i class="fas fa-sign-out-alt" style="color:#dc3545;"></i> <span style="color:#dc3545;">Sign Out</span>
                    </button>
                </form>
            </div>
        </div>

        <div>
            <!-- PROFILE INFO -->
            <div class="profile-section">
                <h3>Personal Information</h3>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="form-row-2">
                        <div class="form-g"><label>First Name</label><input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" placeholder="John"></div>
                        <div class="form-g"><label>Last Name</label><input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" placeholder="Doe"></div>
                    </div>
                    <div class="form-g"><label>Username *</label><input type="text" name="username" value="{{ old('username', $user->username) }}" required></div>
                    <div class="form-g"><label>Email</label><input type="email" value="{{ $user->email }}" disabled style="background:var(--warm-white);color:var(--gray);"></div>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Save Changes</button>
                </form>
            </div>

            <!-- CHANGE PASSWORD -->
            <div class="profile-section">
                <h3>Change Password</h3>
                <form action="{{ route('profile.password') }}" method="POST" style="max-width:400px;">
                    @csrf @method('PATCH')
                    <div class="form-g"><label>Current Password *</label><input type="password" name="current_password" required></div>
                    <div class="form-g"><label>New Password *</label><input type="password" name="password" required minlength="6"></div>
                    <div class="form-g"><label>Confirm New Password *</label><input type="password" name="password_confirmation" required></div>
                    <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-lock"></i> Update Password</button>
                </form>
            </div>

            <!-- ADDRESSES -->
            <div class="profile-section">
                <h3>Saved Addresses</h3>
                @forelse($user->shippingAddresses as $addr)
                <div class="address-item">
                    <div style="font-size:.875rem;line-height:1.7;">
                        <strong>{{ $addr->full_name ?? $user->username }}</strong><br>
                        {{ $addr->address }}, {{ $addr->city }}, {{ $addr->province }}<br>
                        <span style="color:var(--gray);">{{ $addr->phone_number }}</span>
                    </div>
                    <form action="{{ route('profile.address.delete', $addr->shipping_id) }}" method="POST" onsubmit="return confirm('Remove address?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
                @empty
                <p style="color:var(--gray);font-size:.875rem;">No saved addresses yet.</p>
                @endforelse

                <div style="margin-top:1.25rem;border-top:1px solid var(--border);padding-top:1.25rem;">
                    <h4 style="font-size:.9rem;margin-bottom:1rem;font-weight:600;">Add New Address</h4>
                    <form action="{{ route('profile.address.store') }}" method="POST">
                        @csrf
                        <div class="form-row-2">
                            <div class="form-g"><label>Full Name *</label><input type="text" name="full_name" required></div>
                            <div class="form-g"><label>Phone *</label><input type="text" name="phone_number" required></div>
                        </div>
                        <div class="form-g"><label>Address *</label><input type="text" name="address" required></div>
                        <div class="form-row-2">
                            <div class="form-g"><label>City</label><input type="text" name="city"></div>
                            <div class="form-g"><label>Province *</label><input type="text" name="province" required></div>
                        </div>
                        <div class="form-g"><label>Postal Code</label><input type="text" name="postal_code"></div>
                        <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-plus"></i> Add Address</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
