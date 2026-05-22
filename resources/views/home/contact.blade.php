@extends('layouts.app')

@section('title', 'Contact Us')

@push('styles')
<style>
    .contact-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; padding: 5rem 0; align-items: start; }
    .contact-info h2 { font-family: var(--font-display); font-size: 2.25rem; margin-bottom: 1rem; }
    .contact-info p { color: var(--gray); line-height: 1.8; margin-bottom: 2rem; }
    .contact-item { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; }
    .contact-icon { width: 48px; height: 48px; background: var(--warm-white); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: var(--gold); flex-shrink: 0; }
    .contact-item h4 { font-size: .875rem; font-weight: 600; margin-bottom: .2rem; }
    .contact-item p { font-size: .875rem; color: var(--gray); margin: 0; }
    .contact-form-card { background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 2rem; }
    .contact-form-card h3 { font-family: var(--font-display); font-size: 1.25rem; margin-bottom: 1.5rem; }
    .form-g { margin-bottom: 1.1rem; }
    .form-g label { display: block; font-size: .85rem; font-weight: 500; margin-bottom: .4rem; }
    .form-g input, .form-g textarea, .form-g select { width: 100%; padding: .65rem 1rem; border: 1.5px solid var(--border); border-radius: 8px; font-size: .875rem; font-family: var(--font-body); }
    .form-g input:focus, .form-g textarea:focus { outline: none; border-color: var(--gold); }
    .form-g textarea { resize: vertical; min-height: 120px; }
    @media(max-width:768px) { .contact-layout { grid-template-columns: 1fr; gap: 2rem; } }
</style>
@endpush

@section('content')
<div class="page-hero">
    <h1>Contact Us</h1>
    <p>We'd love to hear from you</p>
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> <span>/</span> <span>Contact</span></div>
</div>

<div class="container">
    <div class="contact-layout">
        <div class="contact-info">
            <span class="section-label">Get in Touch</span>
            <h2>We're Here to Help</h2>
            <p>Have a question about an order, product or anything else? Our team is ready to assist you. Reach out through any of the channels below.</p>

            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <h4>Our Location</h4>
                    <p>123 Fashion Avenue, Phnom Penh, Cambodia</p>
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <h4>Email Us</h4>
                    <p>hello@luxestore.com<br>support@luxestore.com</p>
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                <div>
                    <h4>Call Us</h4>
                    <p>+855 23 456 789<br>Mon–Fri, 9am–6pm</p>
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-clock"></i></div>
                <div>
                    <h4>Business Hours</h4>
                    <p>Monday–Friday: 9:00am – 6:00pm<br>Saturday: 10:00am – 4:00pm</p>
                </div>
            </div>

            <div style="margin-top:2rem;">
                <h4 style="font-size:.875rem;font-weight:600;margin-bottom:.75rem;">Follow Us</h4>
                <div style="display:flex;gap:.75rem;">
                    <a href="#" style="width:40px;height:40px;background:var(--black);color:#fff;border-radius:8px;display:flex;align-items:center;justify-content:center;text-decoration:none;"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="width:40px;height:40px;background:var(--black);color:#fff;border-radius:8px;display:flex;align-items:center;justify-content:center;text-decoration:none;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" style="width:40px;height:40px;background:var(--black);color:#fff;border-radius:8px;display:flex;align-items:center;justify-content:center;text-decoration:none;"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>

        <div>
            <div class="contact-form-card">
                <h3>Send a Message</h3>
                @if(session('contact_success'))
                    <div style="background:#d4edda;color:#155724;padding:.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:.875rem;">
                        <i class="fas fa-check-circle"></i> Message sent! We'll reply soon.
                    </div>
                @endif
                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                        <div class="form-g"><label>First Name *</label><input type="text" name="first_name" placeholder="John" value="{{ old('first_name') }}" required></div>
                        <div class="form-g"><label>Last Name *</label><input type="text" name="last_name" placeholder="Doe" value="{{ old('last_name') }}" required></div>
                    </div>
                    <div class="form-g"><label>Email *</label><input type="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required></div>
                    <div class="form-g"><label>Subject</label>
                        <select name="subject">
                            <option value="">Select a subject</option>
                            <option {{ old('subject') == 'Order Inquiry' ? 'selected' : '' }}>Order Inquiry</option>
                            <option {{ old('subject') == 'Product Question' ? 'selected' : '' }}>Product Question</option>
                            <option {{ old('subject') == 'Returns & Refunds' ? 'selected' : '' }}>Returns & Refunds</option>
                            <option {{ old('subject') == 'General Feedback' ? 'selected' : '' }}>General Feedback</option>
                            <option {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="form-g"><label>Message *</label><textarea name="message" placeholder="How can we help you?" required>{{ old('message') }}</textarea></div>
                    @if($errors->any())
                        <div style="color:#721c24;background:#f8d7da;padding:.6rem 1rem;border-radius:6px;font-size:.85rem;margin-bottom:1rem;">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.85rem;">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
