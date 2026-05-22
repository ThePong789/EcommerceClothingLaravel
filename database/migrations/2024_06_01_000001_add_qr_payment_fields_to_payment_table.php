<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Add ABA / ACLEDA QR payment support
 *
 * - Adds 'awaiting_payment' as a valid payment status
 * - Adds 'qr_reference' column for storing QR transaction reference
 * - The payment_method column already accepts any string, so 'aba' and 'acleda'
 *   work without schema changes. This migration just adds the helper column.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment', function (Blueprint $table) {
            // QR transaction reference returned by ABA / ACLEDA webhook
            $table->string('qr_reference')->nullable()->after('transaction_id');

            // Which bank account / merchant ID the QR points to
            $table->string('merchant_id')->nullable()->after('qr_reference');

            // Timestamp when payment was confirmed by the bank (webhook or manual)
            $table->timestamp('confirmed_at')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('payment', function (Blueprint $table) {
            $table->dropColumn(['qr_reference', 'merchant_id', 'confirmed_at']);
        });
    }
};
