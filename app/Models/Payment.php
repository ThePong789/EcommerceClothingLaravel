<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'order_id',
        'payment_method',   // cod | aba | acleda
        'amount',
        'status',           // pending | awaiting_payment | paid | failed | refunded
        'transaction_id',
        'qr_reference',     // QR payload reference / KHQR trace number
        'merchant_id',      // ABA / ACLEDA merchant ID
        'paid_at',
        'confirmed_at',
        'receipt_image',    // Path to uploaded payment receipt screenshot
    ];

    protected $casts = [
        'paid_at'      => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /** Human-readable payment method label */
    public function getMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'aba'    => 'ABA Bank QR',
            'acleda' => 'ACLEDA Bank QR',
            'cod'    => 'Cash on Delivery',
            default  => ucfirst(str_replace('_', ' ', $this->payment_method)),
        };
    }

    /** True if this is a QR-based payment */
    public function isQrPayment(): bool
    {
        return in_array($this->payment_method, ['aba', 'acleda']);
    }

    /** True if payment is complete */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /** True if waiting for user to scan QR */
    public function isAwaitingPayment(): bool
    {
        return $this->status === 'awaiting_payment';
    }
}
