<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Models\User; // thêm dòng này nếu chưa có
// ============================================
// FRONTEND ROUTES
// ============================================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');

// Checkout (require auth)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    
    // Account
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [AccountController::class, 'orderDetail'])->name('orders.detail');
        Route::put('/orders/{order}/cancel', [AccountController::class, 'cancelOrder'])->name('orders.cancel');
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
        Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
        Route::post('/addresses', [AccountController::class, 'addAddress'])->name('addresses.add');
        Route::delete('/addresses/{address}', [AccountController::class, 'deleteAddress'])->name('addresses.delete');
        Route::put('/addresses/{address}/set-default', [AccountController::class, 'setDefaultAddress'])->name('addresses.set-default');
    });
});
// Reviews (require auth)
Route::middleware('auth')->group(function () {
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

// ============================================
// CHAT ROUTES
// ============================================

// Chat routes for users (Frontend)
Route::prefix('chat')->name('chat.')->group(function () {
    Route::post('/init', [ChatController::class, 'initChat'])->name('init');
    Route::post('/send', [ChatController::class, 'sendMessage'])->name('send');
    Route::get('/messages', [ChatController::class, 'getMessages'])->name('messages');
    Route::get('/unread-count', [ChatController::class, 'getUnreadCount'])->name('unread');
});

// Admin chat routes (Backend)
Route::prefix('admin/chat')->middleware(['auth', 'admin'])->name('admin.chat.')->group(function () {
    Route::get('/', [AdminChatController::class, 'index'])->name('index');
    Route::get('/{chat}/messages', [AdminChatController::class, 'getMessages'])->name('messages');
    Route::post('/{chat}/send', [AdminChatController::class, 'sendMessage'])->name('send');
    Route::post('/{chat}/close', [AdminChatController::class, 'closeChat'])->name('close');
    Route::delete('/{chat}', [AdminChatController::class, 'deleteChat'])->name('delete');
    Route::get('/unread-count', [AdminChatController::class, 'getUnreadCount'])->name('unread');
});

// ============================================
// AUTHENTICATION ROUTES
// ============================================

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// ============================================
// ADMIN ROUTES (from previous setup)
// ============================================

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReviewController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('categories', AdminCategoryController::class);
    Route::post('categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    
    Route::resource('products', AdminProductController::class);
    Route::post('products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::post('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    Route::delete('products/{product}/images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.delete-image');
    
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);
    Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{order}/update-payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
    
    Route::resource('customers', CustomerController::class)->only(['index', 'show', 'destroy']);
    
    Route::resource('coupons', CouponController::class);
    Route::post('coupons/{coupon}/toggle-status', [CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
    
    Route::resource('reviews', ReviewController::class)->only(['index', 'show', 'destroy']);
    Route::post('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
});
    Route::get('/ping', function () {
        return 'ok';
});

    Route::get('/run-migrate', function () {
    Artisan::call('migrate', ['--force' => true]);
        return 'Migrated!';
});
    Route::get('/make-admin/{id}', function ($id) {
    $user = User::find($id);
    if (!$user) {
        return 'User không tồn tại';
    }

    // tuỳ theo cấu trúc bảng users của bro:
    // Nếu có cột 'role'
    $user->role = 'admin';

    // HOẶC nếu bro dùng cột 'is_admin' dạng tinyint/bool:
    // $user->is_admin = 1;

    $user->save();

    return 'User '.$id.' đã được set admin';
});
