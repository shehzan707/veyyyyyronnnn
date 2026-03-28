<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\OrderCancellationController;
use App\Http\Controllers\BulkImportController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Category-specific home pages
Route::get('/men', [HomeController::class, 'homeMen'])->name('home.men');
Route::get('/women', [HomeController::class, 'homeWomen'])->name('home.women');
Route::get('/accessories', [HomeController::class, 'homeAccessories'])->name('home.accessories');
Route::get('/footwear', [HomeController::class, 'homeFootwear'])->name('home.footwear');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Gallery - Display all product images from bulk folder
Route::get('/gallery', [GalleryController::class, 'allImages'])->name('gallery.all');
Route::get('/gallery/{category}', [GalleryController::class, 'imagesByCategory'])->name('gallery.category');
Route::get('/api/gallery/images', [GalleryController::class, 'getImagesJson'])->name('gallery.api.images');

// Categories API - For mega menu navigation
Route::get('/api/categories/{parent}', [CategoryController::class, 'getByParent'])->name('api.categories.parent');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/add/{id}', [CartController::class, 'addProduct'])->name('cart.addProduct');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Wishlist
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
Route::post('/wishlist/add/{id}', [WishlistController::class, 'addProduct'])->name('wishlist.addProduct');
Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');

// Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::prefix('auth')->name('password.')->group(function () {
    // Forgot Password Entry
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('forgot');
    Route::post('/check-user', [PasswordResetController::class, 'checkUser'])->name('check-user');
    
    // Reset Method Selection
    Route::get('/reset-method/{userId}', [PasswordResetController::class, 'showResetMethodForm'])->name('reset-method');
    
    // Option 1: Reset with Old Password
    Route::get('/reset-old-password/{userId}', [PasswordResetController::class, 'showOldPasswordForm'])->name('reset-old-password');
    Route::post('/verify-old-password', [PasswordResetController::class, 'verifyOldPassword'])->name('verify-old-password');
    
    // Option 2: Reset with OTP
    Route::get('/verify-identity/{userId}', [PasswordResetController::class, 'showVerifyIdentityForm'])->name('verify-identity');
    Route::post('/verify-identity', [PasswordResetController::class, 'verifyIdentity'])->name('verify-identity-post');
    Route::post('/generate-otp', [PasswordResetController::class, 'generateOtp'])->name('generate-otp');
    Route::post('/resend-otp', [PasswordResetController::class, 'resendOtp'])->name('resend-otp');
    Route::get('/verify-otp/{userId}', [PasswordResetController::class, 'showOtpInputForm'])->name('verify-otp');
    Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp'])->name('verify-otp-post');
    Route::get('/reset-otp-password/{userId}', [PasswordResetController::class, 'showNewPasswordForm'])->name('reset-otp-password');
    Route::post('/reset-otp-password', [PasswordResetController::class, 'resetPasswordWithOtp'])->name('reset-otp-password-post');
});

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->middleware('check-active')->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('check-active')->name('checkout.store');
Route::get('/order-success/{id}', [CheckoutController::class, 'success'])->name('order.success');

// Account
Route::get('/account', [AccountController::class, 'index'])->middleware('check-active')->name('account.index');
Route::post('/account/profile', [AccountController::class, 'updateProfile'])->middleware('check-active')->name('account.updateProfile');
Route::post('/account/address', [AccountController::class, 'addAddress'])->middleware('check-active')->name('account.addAddress');
Route::get('/account/address/{id}', [AccountController::class, 'getAddress'])->middleware('check-active')->name('account.getAddress');
Route::put('/account/address/{id}', [AccountController::class, 'updateAddress'])->middleware('check-active')->name('account.updateAddress');
Route::delete('/account/address/{id}', [AccountController::class, 'deleteAddress'])->middleware('check-active')->name('account.deleteAddress');
Route::get('/account/orders', [AccountController::class, 'orders'])->middleware('check-active')->name('account.orders');
Route::get('/account/orders/{id}', [AccountController::class, 'orderView'])->middleware('check-active')->name('account.order.view');

// Order Cancellation Routes
Route::prefix('order')->name('order.')->middleware('check-active')->group(function () {
    Route::get('/cancellation-reasons', [OrderCancellationController::class, 'getReasons'])->name('cancellation.reasons');
    Route::post('/item/{itemId}/cancel', [OrderCancellationController::class, 'cancelItem'])->name('item.cancel');
    Route::post('/{orderId}/cancel', [OrderCancellationController::class, 'cancelOrder'])->name('cancel');
});

// Coupon
Route::post('/coupon/validate', [\App\Http\Controllers\CouponController::class, 'validateCoupon'])->name('coupon.validate');
Route::get('/coupon/available', [\App\Http\Controllers\CouponController::class, 'getAvailableCoupons'])->name('coupon.available');
Route::post('/coupon/apply', [\App\Http\Controllers\CouponController::class, 'applyCoupon'])->name('coupon.apply');
Route::post('/coupon/remove', [\App\Http\Controllers\CouponController::class, 'removeCoupon'])->name('coupon.remove');

// Bulk Import Routes
Route::prefix('bulk-import')->group(function () {
    Route::get('/', [BulkImportController::class, 'index'])->name('bulk.import.index');
    Route::post('/products', [BulkImportController::class, 'importProducts'])->name('bulk.import.products');
    Route::post('/categories', [BulkImportController::class, 'importCategories'])->name('bulk.import.categories');
    Route::post('/upload-images', [BulkImportController::class, 'uploadImages'])->name('bulk.upload.images');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Analytics
    Route::get('/analytics', [AdminAnalyticsController::class, 'dashboard'])->name('analytics');
    Route::get('/api/analytics', [AdminAnalyticsController::class, 'getAnalyticsData'])->name('analytics.data');
    
    // Products
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    Route::post('/products/{id}/update-stock', [AdminProductController::class, 'updateStock'])->name('products.updateStock');
    Route::post('/products/{id}/toggle-availability', [AdminProductController::class, 'toggleSizeAvailability'])->name('products.toggleAvailability');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    
    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/item/{itemId}/cancel', [OrderCancellationController::class, 'cancelItemByAdmin'])->name('orders.item.cancel');
    
    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggleStatus');
    
    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Banners
    Route::get('/banners', [AdminBannerController::class, 'index'])->name('banners.index');
    Route::post('/banners', [AdminBannerController::class, 'store'])->name('banners.store');
    Route::get('/banners/{id}/edit', [AdminBannerController::class, 'edit'])->name('banners.edit');
    Route::put('/banners/{id}', [AdminBannerController::class, 'update'])->name('banners.update');
    Route::post('/banners/{id}/toggle', [AdminBannerController::class, 'toggle'])->name('banners.toggle');
    Route::delete('/banners/{id}', [AdminBannerController::class, 'destroy'])->name('banners.destroy');

    // Bulk Import (Admin Version)
    Route::get('/bulk-import', [BulkImportController::class, 'index'])->name('bulk.import.index');
    Route::post('/bulk-import/products', [BulkImportController::class, 'importProducts'])->name('bulk.import.products');
    Route::post('/bulk-import/categories', [BulkImportController::class, 'importCategories'])->name('bulk.import.categories');
    Route::post('/bulk-import/upload-images', [BulkImportController::class, 'uploadImages'])->name('bulk.upload.images');
    
    // Logout
    Route::get('/logout', function() {
        session()->forget(['admin_id', 'admin_mobile', 'is_admin']);
        return redirect()->route('login');
    })->name('logout');
});
