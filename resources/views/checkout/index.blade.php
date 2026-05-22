@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
<style>
    /* ── LAYOUT ── */
    .checkout-layout { display: grid; grid-template-columns: 1fr 380px; gap: 2.5rem; padding: 3rem 0; align-items: start; }
    .checkout-section { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; margin-bottom: 1.5rem; }
    .checkout-section h3 { font-family: var(--font-display); font-size: 1.1rem; margin-bottom: 1.25rem; padding-bottom: .75rem; border-bottom: 1px solid var(--border); }

    /* ── ADDRESS ── */
    .address-card { border: 1.5px solid var(--border); border-radius: 8px; padding: 1rem 1.25rem; cursor: pointer; margin-bottom: .75rem; transition: all .2s; display: flex; align-items: flex-start; gap: .75rem; }
    .address-card:hover { border-color: var(--gold); }
    .address-card.selected { border-color: var(--black); background: var(--warm-white); }
    .address-card input[type=radio] { margin-top: 3px; accent-color: var(--black); }
    .address-detail { font-size: .875rem; line-height: 1.6; color: var(--charcoal); }
    .address-detail strong { font-size: .9rem; }

    /* ── PAYMENT OPTIONS ── */
    .payment-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: .75rem; margin-bottom: 1rem; }
    .payment-card { border: 2px solid var(--border); border-radius: 10px; padding: .9rem .75rem; cursor: pointer; transition: all .2s; display: flex; flex-direction: column; align-items: center; gap: .5rem; text-align: center; position: relative; background: #fff; }
    .payment-card:hover { border-color: var(--gold); transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,.07); }
    .payment-card.selected { border-color: var(--black); background: var(--warm-white); box-shadow: 0 4px 20px rgba(0,0,0,.1); }
    .payment-card input[type=radio] { position: absolute; top: .6rem; right: .6rem; accent-color: var(--black); }
    .payment-card .pay-icon { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .payment-card .pay-label { font-size: .78rem; font-weight: 600; color: var(--charcoal); line-height: 1.3; }
    .payment-card .pay-badge { font-size: .65rem; background: var(--gold-light); color: #7a5c1e; border-radius: 20px; padding: .1rem .5rem; font-weight: 600; }

    /* ABA brand colors */
    .icon-aba { background: #e8f4ff; color: #0066cc; }
    /* ACLEDA brand colors */
    .icon-acleda { background: #fff0e8; color: #e05c00; }
    /* COD */
    .icon-cod { background: #e8f8ee; color: #1a9e4a; }

    /* ── QR MODAL ── */
    .qr-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.55); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
    .qr-modal-overlay.show { display: flex; }
    .qr-modal { background: #fff; border-radius: 20px; width: 420px; max-width: 92vw; overflow: hidden; box-shadow: 0 24px 80px rgba(0,0,0,.25); animation: modalIn .3s ease; }
    @keyframes modalIn { from { opacity:0; transform: scale(.93) translateY(20px); } to { opacity:1; transform: scale(1) translateY(0); } }
    .qr-modal-header { padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; }
    .qr-modal-header h4 { font-family: var(--font-display); font-size: 1.15rem; }
    .qr-modal-close { width: 30px; height: 30px; border-radius: 50%; border: 1.5px solid var(--border); background: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: .85rem; color: var(--gray); transition: all .2s; }
    .qr-modal-close:hover { background: var(--warm-white); color: var(--black); }
    .qr-bank-banner { padding: .75rem 1.5rem; display: flex; align-items: center; gap: .75rem; }
    .qr-bank-logo { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem; flex-shrink: 0; }
    .qr-bank-logo.aba-logo { background: #0066cc; color: #fff; }
    .qr-bank-logo.acleda-logo { background: #e05c00; color: #fff; }
    .qr-bank-info h5 { font-size: .95rem; font-weight: 700; }
    .qr-bank-info p { font-size: .8rem; color: var(--gray); }
    .qr-body { padding: 0 1.5rem 1.25rem; }
    .qr-code-wrap { background: var(--warm-white); border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; align-items: center; gap: .75rem; border: 1px solid var(--border); }
    .qr-code-wrap canvas, .qr-code-wrap img { border-radius: 8px; }
    .qr-amount-badge { background: #fff; border: 1.5px solid var(--border); border-radius: 30px; padding: .45rem 1.1rem; font-size: .95rem; font-weight: 700; display: flex; align-items: center; gap: .5rem; }
    .qr-amount-badge span { color: var(--gray); font-weight: 400; font-size: .8rem; }
    .qr-instructions { margin-top: 1rem; }
    .qr-step { display: flex; align-items: flex-start; gap: .75rem; margin-bottom: .6rem; }
    .qr-step-num { width: 22px; height: 22px; border-radius: 50%; background: var(--black); color: #fff; font-size: .7rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
    .qr-step p { font-size: .82rem; color: var(--charcoal); line-height: 1.5; }
    .qr-confirm-btn { width: 100%; padding: .85rem; background: var(--black); color: #fff; border: none; border-radius: 8px; font-size: .95rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: .5rem; transition: background .2s; margin-top: 1rem; font-family: var(--font-body); }
    .qr-confirm-btn:hover { background: var(--charcoal); }
    .qr-timer { text-align: center; font-size: .78rem; color: var(--gray); margin-top: .6rem; }
    .qr-timer b { color: #e05c00; }

    /* ── PAYMENT DETAIL PANELS ── */
    .payment-detail-panel { display: none; background: var(--warm-white); border-radius: 10px; padding: 1.1rem 1.25rem; margin-top: .5rem; border: 1.5px solid var(--border); }
    .payment-detail-panel.show { display: block; animation: fadeIn .25s ease; }
    @keyframes fadeIn { from { opacity:0; transform: translateY(-6px); } to { opacity:1; transform: translateY(0); } }

    /* ── ORDER SUMMARY ── */
    .order-summary-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; position: sticky; top: 90px; }
    .order-item { display: flex; align-items: center; gap: .75rem; padding: .75rem 0; border-bottom: 1px solid #f5f5f5; }
    .order-item:last-of-type { border-bottom: none; }
    .order-item-img { width: 52px; height: 52px; background: var(--warm-white); border-radius: 6px; overflow: hidden; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: var(--gray); font-size: .8rem; }
    .order-item-img img { width: 100%; height: 100%; object-fit: cover; }

    /* ── NEW ADDRESS FORM ── */
    .new-address-form { background: var(--warm-white); border-radius: 8px; padding: 1.25rem; margin-top: 1rem; display: none; }
    .new-address-form.show { display: block; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
    .form-field label { display: block; font-size: .8rem; font-weight: 500; margin-bottom: .35rem; }
    .form-field input, .form-field select { width: 100%; padding: .55rem .85rem; border: 1.5px solid var(--border); border-radius: 6px; font-size: .875rem; font-family: var(--font-body); background: #fff; }
    .form-field input:focus, .form-field select:focus { outline: none; border-color: var(--gold); }

    /* ── SECURE BADGES ── */
    .secure-badges { display: flex; align-items: center; justify-content: center; gap: 1rem; margin-top: .75rem; flex-wrap: wrap; }
    .secure-badge { display: flex; align-items: center; gap: .3rem; font-size: .72rem; color: var(--gray); }

    @media(max-width:768px) {
        .checkout-layout { grid-template-columns: 1fr; }
        .order-summary-card { position: static; }
        .payment-grid { grid-template-columns: 1fr 1fr 1fr; }
    }
    @media(max-width:480px) {
        .payment-grid { grid-template-columns: 1fr; }
    }
</style>
<!-- QRCode.js library for generating QR codes -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endpush

@section('content')
<div class="page-hero">
    <h1>Checkout</h1>
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> <span>/</span> <a href="{{ route('cart') }}">Cart</a> <span>/</span> <span>Checkout</span></div>
</div>

<div class="container">
    <div class="checkout-layout">
        <div>
            <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
                @csrf

                <!-- ── SHIPPING ADDRESS ── -->
                <div class="checkout-section">
                    <h3><i class="fas fa-map-marker-alt" style="color:var(--gold);margin-right:.5rem;"></i> Shipping Address</h3>

                    @if($addresses->isNotEmpty())
                        @foreach($addresses as $addr)
                        <label class="address-card {{ $loop->first ? 'selected' : '' }}" onclick="selectAddress(this)">
                            <input type="radio" name="shipping_id" value="{{ $addr->shipping_id }}" {{ $loop->first ? 'checked' : '' }}>
                            <div class="address-detail">
                                <strong>{{ $addr->full_name ?? auth()->user()->username }}</strong>
                                <br>{{ $addr->address }}, {{ $addr->city }}, {{ $addr->province }}
                                @if($addr->postal_code), {{ $addr->postal_code }}@endif
                                <br><i class="fas fa-phone fa-xs" style="color:var(--gray);"></i> {{ $addr->phone_number }}
                            </div>
                        </label>
                        @endforeach
                        <button type="button" class="btn btn-outline btn-sm" style="margin-top:.5rem;" onclick="toggleNewAddress()">
                            <i class="fas fa-plus"></i> Use Different Address
                        </button>
                    @endif

                    <div class="new-address-form {{ $addresses->isEmpty() ? 'show' : '' }}" id="new-address-form">
                        @if($addresses->isNotEmpty())<p style="font-size:.85rem;margin-bottom:1rem;font-weight:600;">New Address</p>@endif
                        <div class="form-row" style="margin-bottom:.75rem;">
                            <div class="form-field"><label>Full Name *</label><input type="text" name="new_address[full_name]" class="new-addr-field"></div>
                            <div class="form-field"><label>Phone Number *</label><input type="text" name="new_address[phone_number]" class="new-addr-field"></div>
                        </div>
                        <div class="form-field" style="margin-bottom:.75rem;"><label>Address *</label><input type="text" name="new_address[address]" class="new-addr-field"></div>
                        <div class="form-row" style="margin-bottom:.75rem;">
                            <div class="form-field"><label>City</label><input type="text" name="new_address[city]"></div>
                            <div class="form-field"><label>Province *</label><input type="text" name="new_address[province]" class="new-addr-field"></div>
                        </div>
                        <div class="form-field"><label>Postal Code</label><input type="text" name="new_address[postal_code]"></div>
                    </div>
                </div>

                <!-- ── PAYMENT METHOD ── -->
                <div class="checkout-section">
                    <h3><i class="fas fa-wallet" style="color:var(--gold);margin-right:.5rem;"></i> Payment Method</h3>

                    <div class="payment-grid">
                        <!-- ABA Bank -->
                        <div class="payment-card selected" id="pay-card-aba" onclick="selectPayment(this, 'aba')">
                            <input type="radio" name="payment_method" value="aba" id="pay-aba" checked>
                            {{-- <div class="pay-icon icon-aba"><i class="fas fa-qrcode"></i></div> --}}
                            <img src="{{ asset('storage/qr/aba_qr.png') }}" alt="ABA" style="width:40px;height:40px;border-radius:5px;object-fit:cover;">
                            <div class="pay-label">ABA Bank</div>
                            <div class="pay-badge">QR Pay</div>
                        </div>

                        <!-- ACLEDA Bank -->
                        <div class="payment-card" id="pay-card-acleda" onclick="selectPayment(this, 'acleda')">
                            <input type="radio" name="payment_method" value="acleda" id="pay-acleda">
                            {{-- <div class="pay-icon icon-acleda"><i class="fas fa-qrcode"></i></div> --}}
                            <img src="{{ asset('storage/qr/acleda_qr.png') }}" alt="ACLEDA" style="width:45px;height:45px;border-radius:5px;object-fit:cover;">
                            <div class="pay-label">ACLEDA</div>
                            <div class="pay-badge">QR Pay</div>
                        </div>

                        <!-- Cash on Delivery -->
                        <div class="payment-card" id="pay-card-cod" onclick="selectPayment(this, 'cod')">
                            <input type="radio" name="payment_method" value="cod" id="pay-cod">
                            <div class="pay-icon icon-cod"><i class="fas fa-money-bill-wave"></i></div>
                            <div class="pay-label">Cash on Delivery</div>
                            <div class="pay-badge">COD</div>
                        </div>
                    </div>

                    <!-- ABA Info Panel -->
                    <div class="payment-detail-panel show" id="panel-aba">
                        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem;">
                            {{-- <div style="width:40px;height:40px;background:#0066cc;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.85rem;flex-shrink:0;">ABA</div> --}}
                            <img src="{{ asset('storage/qr/aba_qr.png') }}" alt="ABA" style="width:40px;height:40px;border-radius:5px;object-fit:cover;flex-shrink:0;">
                            <div>
                                <div style="font-weight:700;font-size:.9rem;">ABA Bank — QR Payment</div>
                                <div style="font-size:.78rem;color:var(--gray);">Scan QR code with ABA Mobile app</div>
                            </div>
                        </div>
                        <div style="background:#e8f4ff;border-radius:8px;padding:.75rem 1rem;font-size:.82rem;color:#003d80;display:flex;align-items:center;gap:.5rem;">
                            <i class="fas fa-info-circle"></i>
                            After placing your order, you'll see a QR code to complete your payment via ABA Mobile or KHQR.
                        </div>
                    </div>

                    <!-- ACLEDA Info Panel -->
                    <div class="payment-detail-panel" id="panel-acleda">
                        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem;">
                            {{-- <div style="width:40px;height:40px;background:#e05c00;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.75rem;flex-shrink:0;">ACL</div> --}}
                            <img src="{{ asset('storage/qr/acleda_qr.png') }}" alt="ACLEDA" style="width:40px;height:40px;border-radius:8px;object-fit:cover;flex-shrink:0;">
                            <div>
                                <div style="font-weight:700;font-size:.9rem;">ACLEDA Bank — QR Payment</div>
                                <div style="font-size:.78rem;color:var(--gray);">Scan QR code with ACLEDA Unity app</div>
                            </div>
                        </div>
                        <div style="background:#fff0e8;border-radius:8px;padding:.75rem 1rem;font-size:.82rem;color:#7a2e00;display:flex;align-items:center;gap:.5rem;">
                            <i class="fas fa-info-circle"></i>
                            After placing your order, you'll see a QR code to complete your payment via ACLEDA Unity or KHQR.
                        </div>
                    </div>

                    <!-- COD Info Panel -->
                    <div class="payment-detail-panel" id="panel-cod">
                        <div style="background:#e8f8ee;border-radius:8px;padding:.75rem 1rem;font-size:.82rem;color:#0a4d20;display:flex;align-items:center;gap:.5rem;">
                            <i class="fas fa-truck"></i>
                            Pay with cash when your order is delivered to your door. No extra fees.
                        </div>
                    </div>

                    <!-- Secure badges -->
                    <div class="secure-badges" style="margin-top:1rem;">
                        <div class="secure-badge"><i class="fas fa-shield-alt" style="color:var(--gold);"></i> Secure Payment</div>
                        <div class="secure-badge"><i class="fas fa-lock" style="color:var(--gold);"></i> SSL Encrypted</div>
                        <div class="secure-badge"><img src="https://www.nbc.gov.kh/images/logo_nbc.png" style="height:16px;opacity:.6;" alt="NBC" onerror="this.style.display='none'"> NBC Licensed</div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="place-order-btn" style="width:100%;justify-content:center;padding:1rem;font-size:1rem;">
                    <i class="fas fa-lock"></i> Place Order
                </button>
                <p style="text-align:center;font-size:.75rem;color:var(--gray);margin-top:.6rem;">
                    By placing your order, you agree to our <a href="#" style="color:var(--gold);">Terms of Service</a>
                </p>
            </form>
        </div>

        <!-- ── ORDER SUMMARY ── -->
        <div class="order-summary-card">
            <h3 style="font-family:var(--font-display);font-size:1.15rem;margin-bottom:1.25rem;">Order Summary</h3>
            @foreach($cart->items as $item)
            <div class="order-item">
                <div class="order-item-img">
                    @if($item->product && $item->product->product_image)
                        <img src="{{ asset('storage/'.$item->product->product_image) }}" alt="">
                    @else <i class="fas fa-tshirt"></i> @endif
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:.85rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->product->product_name ?? '' }}</div>
                    <div style="font-size:.75rem;color:var(--gray);">Size: {{ $item->size->size_name ?? '' }} · Qty: {{ $item->qty }}</div>
                </div>
                <div style="font-size:.875rem;font-weight:600;white-space:nowrap;">${{ number_format($item->price * $item->qty, 2) }}</div>
            </div>
            @endforeach

            @php $subtotal = $cart->items->sum(fn($i) => $i->price * $i->qty); @endphp
            <div style="border-top:1px solid var(--border);margin-top:.75rem;padding-top:1rem;">
                <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.35rem 0;"><span>Subtotal</span><span>${{ number_format($subtotal, 2) }}</span></div>
                <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.35rem 0;"><span>Shipping</span><span style="color:{{ $subtotal >= 100 ? 'green' : 'var(--gray)' }};">{{ $subtotal >= 100 ? 'FREE' : 'TBD' }}</span></div>
                <div style="display:flex;justify-content:space-between;font-weight:700;font-size:1.05rem;padding:.75rem 0 0;border-top:1px solid var(--border);margin-top:.5rem;"><span>Total</span><span>${{ number_format($subtotal, 2) }}</span></div>
            </div>

            <!-- Payment method summary -->
            <div id="summary-payment-label" style="margin-top:1rem;background:var(--warm-white);border-radius:8px;padding:.75rem 1rem;font-size:.82rem;display:flex;align-items:center;gap:.5rem;">
                <img id="summary-bank-logo" src="{{ asset('storage/qr/aba_qr.png') }}" style="width:20px;height:20px;border-radius:4px;object-fit:cover;">
                <span>Paying via <strong>ABA Bank QR</strong></span>
            </div>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════
     QR PAYMENT MODAL
═══════════════════════════════════════════ -->
<div class="qr-modal-overlay" id="qr-modal-overlay">
    <div class="qr-modal">
        <!-- Header -->
        <div class="qr-modal-header">
            <h4 id="qr-modal-title">Scan to Pay</h4>
            <button class="qr-modal-close" onclick="closeQrModal()"><i class="fas fa-times"></i></button>
        </div>

        <!-- Bank Banner -->
        <div class="qr-bank-banner" id="qr-bank-banner">
            {{-- <div class="qr-bank-logo aba-logo" id="qr-bank-logo-el">ABA</div> --}}
            <div id="qr-bank-logo-el" style="width:48px;height:48px;border-radius:10px;overflow:hidden;flex-shrink:0;">
                <img src="{{ asset('images/aba_qr.png') }}" alt="ABA" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div class="qr-bank-info">
                <h5 id="qr-bank-name">ABA Bank</h5>
                <p id="qr-bank-desc">Open ABA Mobile → Scan QR</p>
            </div>
        </div>

        <!-- Body -->
        <div class="qr-body">
            <div class="qr-code-wrap">
                <div id="qr-code-canvas"></div>
                <div class="qr-amount-badge">
                    <span>Total:</span>
                    <strong id="qr-amount-display">${{ number_format($subtotal, 2) }}</strong>
                </div>
                <div style="font-size:.72rem;color:var(--gray);text-align:center;">Order will be confirmed after payment</div>
            </div>

            <!-- Steps -->
            <div class="qr-instructions">
                <div class="qr-step">
                    <div class="qr-step-num">1</div>
                    <p id="qr-step1">Open <strong>ABA Mobile</strong> app on your phone</p>
                </div>
                <div class="qr-step">
                    <div class="qr-step-num">2</div>
                    <p>Tap <strong>"Scan QR"</strong> and point your camera at the code above</p>
                </div>
                <div class="qr-step">
                    <div class="qr-step-num">3</div>
                    <p>Confirm the amount and tap <strong>"Pay"</strong> to complete</p>
                </div>
                <div class="qr-step">
                    <div class="qr-step-num">4</div>
                    <p>Return here and click <strong>"I've Paid"</strong> below</p>
                </div>
            </div>

            <!-- Confirm button -->
            <button class="qr-confirm-btn" id="qr-confirm-btn" onclick="confirmQrPayment()">
                <i class="fas fa-check-circle"></i> I've Paid — Confirm Order
            </button>
            <div class="qr-timer">QR code valid for <b id="qr-countdown">10:00</b></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── PAYMENT DATA ──
const paymentConfig = {
    aba: {
        name: 'ABA Bank',
        desc: 'Open ABA Mobile → Scan QR',
        logo: 'ABA',
        logoClass: 'aba-logo',
        logoImg: '/storage/qr/aba_qr.png',
        stepLabel: '<strong>ABA Mobile</strong> app on your phone',
        qrData: (amount, order) => `00020101021229370013merchant@aba.com5204000053036400540${amount}5802KH5913LuxeStore6010Phnom Penh6304ABCD`,
        summaryIcon: 'qrcode',
        summaryColor: '#0066cc',
        summaryText: 'ABA Bank QR',
    },
    acleda: {
        name: 'ACLEDA Bank',
        desc: 'Open ACLEDA Unity → Scan QR',
        logo: 'ACL',
        logoClass: 'acleda-logo',
        logoImg: '/storage/qr/acleda_qr.png',
        stepLabel: '<strong>ACLEDA Unity</strong> app on your phone',
        qrData: (amount, order) => `00020101021229420013merchant@acleda.com5204000053036400540${amount}5802KH5913LuxeStore6010Phnom Penh6304EFGH`,
        summaryIcon: 'qrcode',
        summaryColor: '#e05c00',
        summaryText: 'ACLEDA Bank QR',
    },
    cod: {
        summaryIcon: 'money-bill-wave',
        summaryColor: '#1a9e4a',
        summaryText: 'Cash on Delivery',
        logoImg: null,
    }
};

let currentMethod = 'aba';
let qrCountdownInterval = null;

// ── SELECT PAYMENT ──
function selectPayment(el, method) {
    document.querySelectorAll('.payment-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input').checked = true;
    currentMethod = method;

    // Show/hide detail panels
    document.querySelectorAll('.payment-detail-panel').forEach(p => p.classList.remove('show'));
    const panel = document.getElementById('panel-' + method);
    if (panel) panel.classList.add('show');

    // Update summary label
    const cfg = paymentConfig[method];
    const label = document.getElementById('summary-payment-label');
    const iconHtml = cfg.logoImg
        ? `<img src="${cfg.logoImg}" style="width:20px;height:20px;border-radius:4px;object-fit:cover;">`
        : `<i class="fas fa-${cfg.summaryIcon}" style="color:${cfg.summaryColor};"></i>`;
    label.innerHTML = `${iconHtml} <span>Paying via <strong>${cfg.summaryText}</strong></span>`;
}

// ── ADDRESS HELPERS ──
function setNewAddressRequired(required) {
    document.querySelectorAll('.new-addr-field').forEach(el => {
        if (required) el.setAttribute('required', '');
        else el.removeAttribute('required');
    });
}
@if($addresses->isNotEmpty())
setNewAddressRequired(false);
@else
setNewAddressRequired(true);
@endif

function selectAddress(el) {
    document.querySelectorAll('.address-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input').checked = true;
    const form = document.getElementById('new-address-form');
    form.classList.remove('show');
    setNewAddressRequired(false);
}
function toggleNewAddress() {
    const form = document.getElementById('new-address-form');
    form.classList.toggle('show');
    if (form.classList.contains('show')) {
        document.querySelectorAll('.address-card').forEach(c => { c.classList.remove('selected'); c.querySelector('input').checked = false; });
        setNewAddressRequired(true);
    } else {
        setNewAddressRequired(false);
        const first = document.querySelector('.address-card');
        if (first) { first.classList.add('selected'); first.querySelector('input').checked = true; }
    }
}

// ── QR MODAL ──
let orderTotal = {{ $subtotal }};

document.getElementById('checkout-form').addEventListener('submit', function(e) {
    if (currentMethod === 'aba' || currentMethod === 'acleda') {
        e.preventDefault();
        openQrModal(currentMethod);
    }
    // COD submits normally
});

function openQrModal(method) {
    const cfg = paymentConfig[method];
    const overlay = document.getElementById('qr-modal-overlay');

    // Update modal content
    document.getElementById('qr-modal-title').textContent = `Pay with ${cfg.name}`;
    document.getElementById('qr-bank-name').textContent = cfg.name;
    document.getElementById('qr-bank-desc').textContent = cfg.desc;
    document.getElementById('qr-step1').innerHTML = `Open ${cfg.stepLabel}`;

    const logoEl = document.getElementById('qr-bank-logo-el');
    logoEl.innerHTML = '<img src="' + cfg.logoImg + '" alt="' + cfg.name + '" style="width:100%;height:100%;object-fit:cover;">';

    document.getElementById('qr-amount-display').textContent = '$' + orderTotal.toFixed(2);

    // Generate QR
    const canvas = document.getElementById('qr-code-canvas');
    canvas.innerHTML = '';
    const qrContent = cfg.qrData(orderTotal.toFixed(2), 'ORD' + Date.now());
    new QRCode(canvas, {
        text: qrContent,
        width: 200,
        height: 200,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M
    });

    // Timer countdown 10 min
    clearInterval(qrCountdownInterval);
    let seconds = 600;
    function updateTimer() {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        document.getElementById('qr-countdown').textContent = `${m}:${s.toString().padStart(2,'0')}`;
        if (seconds <= 0) { clearInterval(qrCountdownInterval); document.getElementById('qr-countdown').textContent = 'Expired'; }
        seconds--;
    }
    updateTimer();
    qrCountdownInterval = setInterval(updateTimer, 1000);

    overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeQrModal() {
    document.getElementById('qr-modal-overlay').classList.remove('show');
    document.body.style.overflow = '';
    clearInterval(qrCountdownInterval);
}

function confirmQrPayment() {
    const btn = document.getElementById('qr-confirm-btn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Confirming...';
    btn.disabled = true;
    clearInterval(qrCountdownInterval);
    // Submit the original form
    setTimeout(() => {
        document.getElementById('checkout-form').submit();
    }, 800);
}

// Close on overlay click
document.getElementById('qr-modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeQrModal();
});
</script>
@endpush
