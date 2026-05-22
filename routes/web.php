<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, HomeController, CartController,
    OrderController, ReviewController, ProfileController, ContactController
};
use App\Http\Controllers\Admin;
use App\Http\Controllers\Staff;

// ─── Public routes ────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/shop/{id}', [HomeController::class, 'productDetail'])->name('shop.show');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/blog/{id}', [HomeController::class, 'blogDetail'])->name('blog.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// ─── Auth routes ──────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Authenticated user routes ────────────────────────────────────
Route::middleware('auth')->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout & Orders
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('checkout.place');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/receipt', [OrderController::class, 'uploadReceipt'])->name('orders.receipt.upload');

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/address', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
    Route::delete('/profile/address/{id}', [ProfileController::class, 'deleteAddress'])->name('profile.address.delete');
});

// ─── Admin routes ─────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', Admin\ProductController::class);
    Route::resource('categories', Admin\CategoryController::class)->except(['show']);
    Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/orders/{id}/payment-status', [Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.payment-status');
    Route::resource('users', Admin\UserController::class)->except(['show', 'create', 'edit']);
    Route::get('/reviews', [Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{id}/approve', [Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{id}', [Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::resource('blog', Admin\BlogController::class);
    Route::get('/contact', [Admin\ContactController::class, 'index'])->name('contact.index');
    Route::get('/contact/{id}', [Admin\ContactController::class, 'show'])->name('contact.show');
    Route::delete('/contact/{id}', [Admin\ContactController::class, 'destroy'])->name('contact.destroy');
});

// ─── Staff routes ─────────────────────────────────────────────────
Route::middleware(['auth', 'staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [Staff\DashboardController::class, 'index'])->name('dashboard');
    // Staff can manage orders and reviews
    Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/orders/{id}/payment-status', [Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.payment-status');
    Route::get('/reviews', [Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{id}/approve', [Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{id}', [Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::resource('products', Admin\ProductController::class);
    Route::resource('categories', Admin\CategoryController::class)->except(['show']);
});
