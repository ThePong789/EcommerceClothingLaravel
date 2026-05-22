<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Add receipt_image to payment table
 * Allows customers to upload a payment screenshot/receipt
 * so the admin can verify it before approving the order.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment', function (Blueprint $table) {
            $table->string('receipt_image')->nullable()->after('confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('payment', function (Blueprint $table) {
            $table->dropColumn('receipt_image');
        });
    }
};
