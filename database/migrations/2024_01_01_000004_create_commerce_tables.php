<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->id('cart_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('cart_item_id');
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('size_id');
            $table->integer('qty');
            $table->decimal('price', 10, 2);

            $table->foreign('cart_id')->references('cart_id')->on('cart')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('size_id')->references('size_id')->on('sizes')->onDelete('cascade');
        });

        Schema::create('shipping', function (Blueprint $table) {
            $table->id('shipping_id');
            $table->string('phone_number');
            $table->string('address');
            $table->string('province');
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('full_name')->nullable();
            $table->boolean('is_default')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shipping_id')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->string('status')->default('pending'); // pending, processing, shipped, delivered, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('shipping_id')->references('shipping_id')->on('shipping')->onDelete('set null');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('size_id');
            $table->integer('qty');
            $table->decimal('price', 10, 2);

            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('size_id')->references('size_id')->on('sizes')->onDelete('cascade');
        });

        Schema::create('payment', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('order_id');
            $table->string('payment_method'); // cod, bank_transfer, credit_card, etc.
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, paid, failed, refunded
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
        });

        Schema::create('blog', function (Blueprint $table) {
            $table->id('blog_id');
            $table->string('author_name');
            $table->string('author_image')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
        });

        Schema::create('blog_details', function (Blueprint $table) {
            $table->id('blog_detail_id');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->longText('description');
            $table->string('blog_image')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('tags')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedBigInteger('blog_id');
            $table->timestamps();

            $table->foreign('blog_id')->references('blog_id')->on('blog')->onDelete('cascade');
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->string('review_title');
            $table->longText('description');
            $table->integer('rating')->default(5);
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('blog_details');
        Schema::dropIfExists('blog');
        Schema::dropIfExists('payment');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('shipping');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('cart');
    }
};
