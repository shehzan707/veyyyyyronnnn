<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BulkImportController;
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

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order-success/{id}', [CheckoutController::class, 'success'])->name('order.success');

// Account
Route::get('/account', [AccountController::class, 'index'])->name('account.index');
Route::post('/account/profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
Route::post('/account/address', [AccountController::class, 'addAddress'])->name('account.addAddress');
Route::put('/account/address/{id}', [AccountController::class, 'updateAddress'])->name('account.updateAddress');
Route::delete('/account/address/{id}', [AccountController::class, 'deleteAddress'])->name('account.deleteAddress');
Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');
Route::get('/account/orders/{id}', [AccountController::class, 'orderView'])->name('account.order.view');

// Coupon
Route::post('/coupon/validate', [\App\Http\Controllers\CouponController::class, 'validateCoupon'])->name('coupon.validate');

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
    
    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    
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
