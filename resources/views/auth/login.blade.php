<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | LuxeStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --black:#0a0a0a;--gold:#c9a84c;--cream:#faf8f4;--border:#e8e4de;--font-display:'Playfair Display',serif;--font-body:'DM Sans',sans-serif; }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:var(--font-body);background:var(--cream);min-height:100vh;display:grid;grid-template-columns:1fr 1fr;}
        .auth-brand{height: 100vh;background:var(--black);display:flex;flex-direction:column;justify-content:center;align-items:center;padding:4rem;position:relative;overflow:hidden;}
        .auth-brand::before{content:'';position:absolute;width:500px;height:500px;border:1px solid rgba(201,168,76,.1);border-radius:50%;top:50%;left:50%;transform:translate(-50%,-50%);}
        .auth-brand::after{content:'';position:absolute;width:300px;height:300px;border:1px solid rgba(201,168,76,.08);border-radius:50%;top:50%;left:50%;transform:translate(-50%,-50%);}
        .brand-logo{font-family:var(--font-display);font-size:2.5rem;color:#fff;margin-bottom:1.5rem;z-index:1;}
        .brand-logo span{color:var(--gold);}
        .brand-tagline{color:rgba(255,255,255,.5);font-size:.95rem;text-align:center;max-width:300px;line-height:1.7;z-index:1;}
        .auth-form-wrap{display:flex;align-items:center;justify-content:center;padding:3rem 4rem;}
        .auth-form{width:100%;max-width:420px;}
        .auth-form h2{font-family:var(--font-display);font-size:2rem;color:var(--black);margin-bottom:.5rem;}
        .auth-form p{color:#888;font-size:.9rem;margin-bottom:2rem;}
        .form-group{margin-bottom:1.25rem;}
        .form-label{display:block;font-size:.85rem;font-weight:500;margin-bottom:.4rem;color:#444;}
        .form-control{width:100%;padding:.7rem 1rem;border:1.5px solid var(--border);border-radius:8px;font-size:.9rem;font-family:var(--font-body);color:var(--black);background:#fff;transition:border-color .2s;}
        .form-control:focus{outline:none;border-color:var(--gold);}
        .input-icon{position:relative;}
        .input-icon .form-control{padding-left:2.75rem;}
        .input-icon i{position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:#aaa;font-size:.9rem;}
        .btn-auth{width:100%;padding:.8rem;background:var(--black);color:#fff;border:none;border-radius:8px;font-size:.95rem;font-weight:600;cursor:pointer;font-family:var(--font-body);transition:background .2s;margin-top:.5rem;}
        .btn-auth:hover{background:#333;}
        .auth-link{text-align:center;margin-top:1.5rem;font-size:.875rem;color:#888;}
        .auth-link a{color:var(--gold);font-weight:600;text-decoration:none;}
        .error-msg{color:#dc3545;font-size:.8rem;margin-top:.3rem;}
        .alert-error{background:#fee2e2;color:#991b1b;padding:.75rem 1rem;border-radius:8px;font-size:.875rem;margin-bottom:1rem;border:1px solid #fecaca;}
        .divider{display:flex;align-items:center;gap:.75rem;margin:1.5rem 0;color:#bbb;font-size:.8rem;}
        .divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--border);}
        @media(max-width:768px){body{grid-template-columns:1fr;}.auth-brand{display:none;}.auth-form-wrap{padding:2rem 1.5rem;}}
    </style>
</head>
<body>
<div class="auth-brand">
    <div class="brand-logo">Luxe<span>Store</span></div>
    <p class="brand-tagline">Premium fashion & lifestyle products for the modern individual who demands excellence.</p>
</div>
<div class="auth-form-wrap">
    <div class="auth-form">
        <h2>Welcome back</h2>
        <p>Sign in to your account to continue</p>

        @if($errors->any())
            <div class="alert-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
                <label style="display:flex;align-items:center;gap:.4rem;font-size:.875rem;cursor:pointer;">
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div>
            <button type="submit" class="btn-auth">Sign In <i class="fas fa-arrow-right" style="margin-left:.3rem;"></i></button>
        </form>

        <div class="divider">or</div>

        <div class="auth-link">
            Don't have an account? <a href="{{ route('register') }}">Create one</a>
        </div>
        <div class="auth-link" style="margin-top:.75rem;">
            <a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> Back to store</a>
        </div>
    </div>
</div>
</body>
</html>
