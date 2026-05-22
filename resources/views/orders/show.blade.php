@extends('layouts.app')

@section('title', 'Order '.$order->order_number)

@push('styles')
<style>
    .order-detail-wrap { max-width: 860px; margin: 3rem auto; }
    .detail-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; margin-bottom: 1.5rem; overflow: hidden; }
    .detail-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); background: var(--warm-white); display: flex; align-items: center; justify-content: space-between; }
    .detail-card-header h3 { font-family: var(--font-display); font-size: 1.05rem; }
    .detail-card-body { padding: 1.5rem; }

    /* ── STATUS TRACKER ── */
    .order-status-track { display: flex; align-items: center; padding: 1.5rem; gap: 0; }
    .status-step { flex: 1; text-align: center; position: relative; }
    .status-step::before { content: ''; position: absolute; top: 16px; left: -50%; width: 100%; height: 2px; background: var(--border); z-index: 0; }
    .status-step:first-child::before { display: none; }
    .status-dot { width: 32px; height: 32px; border-radius: 50%; background: var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto .5rem; position: relative; z-index: 1; font-size: .75rem; color: #fff; }
    .status-dot.done { background: var(--gold); }
    .status-dot.active { background: var(--black); }
    .status-label { font-size: .75rem; color: var(--gray); }
    .status-label.done, .status-label.active { color: var(--black); font-weight: 600; }

    /* ── QR PAYMENT CARD ── */
    .qr-payment-card { background: #fff; border-radius: 16px; margin-bottom: 1.5rem; overflow: hidden; border: 2px solid transparent; }
    .qr-payment-card.aba-card  { border-color: #0066cc; box-shadow: 0 4px 24px rgba(0,102,204,.12); }
    .qr-payment-card.acleda-card { border-color: #e05c00; box-shadow: 0 4px 24px rgba(224,92,0,.12); }

    .qr-card-header { padding: 1.1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; }
    .qr-card-header.aba-header { background: linear-gradient(135deg, #0066cc 0%, #004a99 100%); color: #fff; }
    .qr-card-header.acleda-header { background: linear-gradient(135deg, #e05c00 0%, #b34700 100%); color: #fff; }

    .qr-header-left { display: flex; align-items: center; gap: .85rem; }
    .qr-bank-logo-sm { width: 42px; height: 42px; border-radius: 10px; background: rgba(255,255,255,.2); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .85rem; color: #fff; letter-spacing: .5px; }
    .qr-bank-title { font-family: var(--font-display); font-size: 1.1rem; }
    .qr-bank-subtitle { font-size: .78rem; opacity: .85; margin-top: 1px; }
    .qr-status-pill { background: rgba(255,255,255,.25); border-radius: 20px; padding: .25rem .85rem; font-size: .75rem; font-weight: 700; display: flex; align-items: center; gap: .35rem; }

    .qr-card-body { padding: 1.75rem 1.5rem; display: flex; gap: 2rem; align-items: flex-start; }
    @media(max-width:600px){ .qr-card-body { flex-direction: column; align-items: center; } }

    /* QR code container */
    .qr-code-box { flex-shrink: 0; text-align: center; }
    .qr-code-frame { background: #fff; border: 3px solid var(--border); border-radius: 16px; padding: 14px; display: inline-block; position: relative; }
    .qr-code-frame.aba-frame  { border-color: #0066cc; }
    .qr-code-frame.acleda-frame { border-color: #e05c00; }
    .qr-corner { position: absolute; width: 16px; height: 16px; }
    .qr-corner.tl { top: -2px; left: -2px; border-top: 4px solid; border-left: 4px solid; border-radius: 4px 0 0 0; }
    .qr-corner.tr { top: -2px; right: -2px; border-top: 4px solid; border-right: 4px solid; border-radius: 0 4px 0 0; }
    .qr-corner.bl { bottom: -2px; left: -2px; border-bottom: 4px solid; border-left: 4px solid; border-radius: 0 0 0 4px; }
    .qr-corner.br { bottom: -2px; right: -2px; border-bottom: 4px solid; border-right: 4px solid; border-radius: 0 0 4px 0; }
    .aba-frame .qr-corner  { border-color: #0066cc; }
    .acleda-frame .qr-corner { border-color: #e05c00; }
    .qr-label { font-size: .72rem; color: var(--gray); margin-top: .6rem; font-weight: 500; }

    /* Right side info */
    .qr-info { flex: 1; }
    .qr-amount-block { margin-bottom: 1.25rem; }
    .qr-amount-block .amount-label { font-size: .78rem; color: var(--gray); margin-bottom: .2rem; }
    .qr-amount-block .amount-value { font-family: var(--font-display); font-size: 2rem; font-weight: 700; color: var(--black); line-height: 1; }
    .qr-amount-block .order-ref { font-size: .78rem; color: var(--gray); margin-top: .3rem; }

    .qr-steps { list-style: none; padding: 0; margin-bottom: 1.25rem; }
    .qr-steps li { display: flex; align-items: flex-start; gap: .65rem; margin-bottom: .55rem; font-size: .83rem; color: var(--charcoal); line-height: 1.5; }
    .qr-step-dot { width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .65rem; font-weight: 700; color: #fff; flex-shrink: 0; margin-top: 1px; }
    .aba-dot  { background: #0066cc; }
    .acleda-dot { background: #e05c00; }

    .qr-timer-bar { background: var(--warm-white); border-radius: 8px; padding: .65rem 1rem; display: flex; align-items: center; gap: .6rem; font-size: .82rem; margin-bottom: 1rem; }
    .qr-timer-bar i { color: #e05c00; }
    #qr-time-remaining { font-weight: 700; color: #e05c00; }

    .btn-paid { padding: .85rem 1.5rem; border-radius: 8px; border: none; font-size: .95rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: .5rem; font-family: var(--font-body); transition: all .2s; width: 100%; justify-content: center; }
    .btn-paid.aba-btn  { background: #0066cc; color: #fff; }
    .btn-paid.aba-btn:hover  { background: #004a99; }
    .btn-paid.acleda-btn { background: #e05c00; color: #fff; }
    .btn-paid.acleda-btn:hover { background: #b34700; }

    .qr-note { font-size: .73rem; color: var(--gray); text-align: center; margin-top: .5rem; }

    /* App download badges */
    .app-badges { display: flex; gap: .6rem; margin-top: .85rem; flex-wrap: wrap; }
    .app-badge { background: var(--warm-white); border: 1px solid var(--border); border-radius: 8px; padding: .45rem .85rem; font-size: .75rem; font-weight: 600; color: var(--charcoal); display: flex; align-items: center; gap: .4rem; text-decoration: none; }
    .app-badge:hover { border-color: var(--gold); }

    /* Paid confirmation */
    .paid-banner { background: #e8f8ee; border: 1.5px solid #a7f0c4; border-radius: 12px; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
    .paid-banner i { font-size: 1.75rem; color: #1a9e4a; }
    .paid-banner h4 { font-size: 1rem; font-weight: 700; color: #0a4d20; }
    .paid-banner p { font-size: .82rem; color: #1a4a27; margin-top: .2rem; }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endpush

@section('content')
<div class="page-hero" style="padding:2.5rem;">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> <span>/</span>
        <a href="{{ route('orders.index') }}">Orders</a> <span>/</span>
        <span>{{ $order->order_number }}</span>
    </div>
</div>

<div class="container">
    <div class="order-detail-wrap">

        {{-- ── SUCCESS FLASH ── --}}
        @if(session('success'))
        <div style="background:#e8f8ee;border:1.5px solid #a7f0c4;border-radius:12px;padding:1rem 1.5rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:.75rem;color:#0a4d20;">
            <i class="fas fa-check-circle" style="font-size:1.2rem;color:#1a9e4a;"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        {{-- Paid confirmation banner --}}
        @if($order->payment && $order->payment->status === 'paid')
        <div class="paid-banner">
            <i class="fas fa-check-circle"></i>
            <div>
                <h4>Payment Confirmed</h4>
                <p>Your {{ strtoupper($order->payment->payment_method) }} payment of ${{ number_format($order->payment->amount, 2) }} was received. Thank you!</p>
            </div>
        </div>
        @endif

        {{-- ── STATUS TRACKER ── --}}
        @php
            $steps = ['pending','processing','shipped','delivered'];
            $currentIdx = array_search($order->status, $steps);
        @endphp
        @if($order->status !== 'cancelled')
        <div class="detail-card">
            <div class="order-status-track">
                @foreach($steps as $i => $step)
                <div class="status-step">
                    <div class="status-dot {{ $currentIdx !== false && $i < $currentIdx ? 'done' : ($currentIdx === $i ? 'active' : '') }}">
                        <i class="fas fa-{{ ['pending'=>'clock','processing'=>'cogs','shipped'=>'truck','delivered'=>'check'][$step] }}"></i>
                    </div>
                    <div class="status-label {{ $currentIdx !== false && $i <= $currentIdx ? ($currentIdx === $i ? 'active' : 'done') : '' }}">{{ ucfirst($step) }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div style="background:#fee2e2;border:1px solid #fecaca;border-radius:12px;padding:1.25rem 1.5rem;margin-bottom:1.5rem;color:#991b1b;display:flex;align-items:center;gap:.75rem;">
            <i class="fas fa-times-circle fa-lg"></i> This order has been cancelled.
        </div>
        @endif

        {{-- ── ORDER ITEMS ── --}}
        <div class="detail-card">
            <div class="detail-card-header">
                <h3>Order Items</h3>
                <span style="font-size:.85rem;color:var(--gray);">{{ $order->order_number }} · {{ $order->created_at->format('M d, Y') }}</span>
            </div>
            <div class="detail-card-body" style="padding:0;">
                @foreach($order->items as $item)
                <div style="display:flex;align-items:center;gap:1rem;padding:1.1rem 1.5rem;border-bottom:1px solid #f5f5f5;">
                    <div style="width:60px;height:60px;background:var(--warm-white);border-radius:8px;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:var(--gray);">
                        @if($item->product && $item->product->product_image)
                            <img src="{{ asset('storage/'.$item->product->product_image) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else <i class="fas fa-tshirt"></i> @endif
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:.9rem;">{{ $item->product->product_name ?? 'Product' }}</div>
                        <div style="font-size:.8rem;color:var(--gray);">Size: {{ $item->size->size_name ?? 'N/A' }} · Qty: {{ $item->qty }}</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-weight:700;">${{ number_format($item->price * $item->qty, 2) }}</div>
                        <div style="font-size:.75rem;color:var(--gray);">${{ number_format($item->price, 2) }} each</div>
                    </div>
                </div>
                @endforeach
                <div style="padding:1.1rem 1.5rem;display:flex;justify-content:flex-end;">
                    <div style="text-align:right;">
                        <div style="font-size:.875rem;color:var(--gray);">Order Total</div>
                        <div style="font-size:1.5rem;font-family:var(--font-display);font-weight:700;">${{ number_format($order->total_price, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
            {{-- SHIPPING --}}
            @if($order->shipping)
            <div class="detail-card">
                <div class="detail-card-header"><h3><i class="fas fa-map-marker-alt" style="color:var(--gold);margin-right:.4rem;"></i> Shipping Address</h3></div>
                <div class="detail-card-body" style="font-size:.875rem;line-height:1.8;">
                    <strong>{{ $order->shipping->full_name }}</strong><br>
                    {{ $order->shipping->address }}<br>
                    {{ $order->shipping->city }}, {{ $order->shipping->province }}<br>
                    @if($order->shipping->postal_code){{ $order->shipping->postal_code }}<br>@endif
                    <i class="fas fa-phone" style="color:var(--gray);"></i> {{ $order->shipping->phone_number }}
                </div>
            </div>
            @endif

            {{-- PAYMENT INFO --}}
            @if($order->payment)
            <div class="detail-card">
                <div class="detail-card-header"><h3><i class="fas fa-wallet" style="color:var(--gold);margin-right:.4rem;"></i> Payment</h3></div>
                <div class="detail-card-body" style="font-size:.875rem;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;">
                        <span>Method</span>
                        <strong style="display:flex;align-items:center;gap:.4rem;">
                            @if($order->payment->payment_method === 'aba')
                                <img src="{{ asset('storage/qr/aba_logo.avif') }}" alt="ABA" style="width:70px;height:30px;border-radius:4px;object-fit:cover;"> 
                            @elseif($order->payment->payment_method === 'acleda')
                                <img src="{{ asset('storage/qr/ac_logo.png') }}" alt="ACLEDA" style="width:91.7px;height:25px;border-radius:4px;object-fit:cover;">
                            @else
                                <i class="fas fa-money-bill-wave" style="color:#1a9e4a;"></i> Cash on Delivery
                            @endif
                        </strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;"><span>Amount</span><strong>${{ number_format($order->payment->amount, 2) }}</strong></div>
                    <div style="display:flex;justify-content:space-between;">
                        <span>Status</span>
                        <span class="badge
                            @if($order->payment->status === 'paid') badge-success
                            @elseif($order->payment->status === 'awaiting_payment') badge-warning
                            @else badge-warning @endif">
                            @if($order->payment->status === 'awaiting_payment') Awaiting Payment
                            @else {{ ucfirst($order->payment->status) }} @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- RECEIPT UPLOAD (only for QR payments that are awaiting or pending) --}}
            @if(in_array($order->payment->payment_method, ['aba','acleda']) && $order->payment->status !== 'paid')
            <div class="detail-card">
                <div class="detail-card-header">
                    <h3><i class="fas fa-receipt" style="color:var(--gold);margin-right:.4rem;"></i> Upload Payment Receipt</h3>
                </div>
                <div class="detail-card-body">
                    @if(session('success'))
                        <div style="background:#d1fae5;color:#065f46;border-radius:8px;padding:.75rem 1rem;margin-bottom:1rem;font-size:.875rem;display:flex;align-items:center;gap:.5rem;">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    {{-- Show existing receipt if already uploaded --}}
                    @if($order->payment->receipt_image)
                        <div style="margin-bottom:1rem;text-align:center;">
                            <p style="font-size:.8rem;color:var(--gray);margin-bottom:.5rem;"><i class="fas fa-check-circle" style="color:#1a9e4a;"></i> Receipt uploaded — waiting for admin review</p>
                            <a href="{{ asset('storage/'.$order->payment->receipt_image) }}" target="_blank">
                                <img src="{{ asset('storage/'.$order->payment->receipt_image) }}"
                                     alt="Payment Receipt"
                                     style="max-width:100%;max-height:260px;border-radius:10px;border:2px solid var(--border);cursor:zoom-in;">
                            </a>
                        </div>
                        <p style="font-size:.8rem;color:var(--gray);text-align:center;margin-bottom:.75rem;">Want to replace it? Upload a new one below.</p>
                    @else
                        <p style="font-size:.875rem;color:var(--gray);margin-bottom:1rem;">
                            <i class="fas fa-info-circle"></i>
                            After completing your QR payment, please upload a screenshot of your payment confirmation so the admin can verify and approve your order.
                        </p>
                    @endif

                    <form action="{{ route('orders.receipt.upload', $order->order_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @error('receipt_image')
                            <div style="background:#fee2e2;color:#991b1b;border-radius:8px;padding:.65rem 1rem;margin-bottom:.75rem;font-size:.8rem;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror

                        {{-- Drag-and-drop / click upload area --}}
                        <label for="receipt-file-input" id="receipt-drop-zone"
                               style="display:block;border:2px dashed var(--border);border-radius:12px;padding:1.5rem;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;background:#fafafa;">
                            <i class="fas fa-cloud-upload-alt" style="font-size:2rem;color:var(--gray);margin-bottom:.5rem;display:block;"></i>
                            <span id="receipt-drop-label" style="font-size:.875rem;color:var(--gray);">
                                Click to choose or drag &amp; drop your receipt image here
                            </span>
                            <span style="display:block;font-size:.75rem;color:var(--gray);margin-top:.3rem;">JPG, PNG, WEBP — max 5 MB</span>
                        </label>
                        <input type="file" id="receipt-file-input" name="receipt_image" accept="image/*"
                               style="display:none;" onchange="previewReceipt(this)">

                        {{-- Live preview --}}
                        <div id="receipt-preview" style="display:none;margin-top:1rem;text-align:center;">
                            <img id="receipt-preview-img" src="" alt="Preview"
                                 style="max-width:100%;max-height:220px;border-radius:10px;border:2px solid var(--border);">
                            <p style="font-size:.75rem;color:var(--gray);margin-top:.4rem;" id="receipt-preview-name"></p>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1rem;">
                            <i class="fas fa-paper-plane"></i> Submit Receipt
                        </button>
                    </form>
                </div>
            </div>
            @endif

            @endif
        </div>

        <div style="margin-top:1.5rem;display:flex;gap:1rem;">
            <a href="{{ route('orders.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Orders</a>
            <a href="{{ route('shop') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewReceipt(input) {
    const preview = document.getElementById('receipt-preview');
    const img = document.getElementById('receipt-preview-img');
    const label = document.getElementById('receipt-drop-label');
    const name = document.getElementById('receipt-preview-name');
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            preview.style.display = 'block';
            label.textContent = file.name;
            name.textContent = (file.size / 1024).toFixed(1) + ' KB';
        };
        reader.readAsDataURL(file);
    }
}
// Drag-and-drop styling
const zone = document.getElementById('receipt-drop-zone');
if (zone) {
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor='#666';zone.style.background='#f0f0f0'; });
    zone.addEventListener('dragleave', () => { zone.style.borderColor='';zone.style.background='#fafafa'; });
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.style.borderColor='';zone.style.background='#fafafa';
        const input = document.getElementById('receipt-file-input');
        input.files = e.dataTransfer.files;
        previewReceipt(input);
    });
}
</script>

@endpush
