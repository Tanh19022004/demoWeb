<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AnalyticsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Frontend Routes (không yêu cầu đăng nhập)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [HomeController::class, 'products'])->name('products');
Route::get('/product/{slug}', [HomeController::class, 'show'])->name('product.show');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Frontend Routes (yêu cầu đăng nhập)
Route::middleware('auth')->group(function () {
    // Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Review Routes
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin Routes
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // User Management Routes
        Route::resource('users', UserController::class);
        Route::post('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.status');

        // Profile Routes  
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // Settings Routes
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        // Analytics Routes
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/', [AnalyticsController::class, 'index'])->name('index');
            Route::get('/sales', [AnalyticsController::class, 'sales'])->name('sales');
            Route::get('/products', [AnalyticsController::class, 'products'])->name('products');
            Route::get('/customers', [AnalyticsController::class, 'customers'])->name('customers');
        });

        // Category Routes
        Route::resource('categories', CategoryController::class);
        
        // Product Routes 
        Route::resource('products', ProductController::class);
        Route::put('products/{product}/delete-image', [ProductController::class, 'deleteImage'])->name('products.deleteImage');
        Route::post('products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
        Route::post('products/{product}/status', [ProductController::class, 'updateStatus'])->name('products.status');

        // Order Routes
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
        Route::post('orders/{order}/process', [AdminOrderController::class, 'process'])->name('orders.process');
        Route::post('orders/{order}/complete', [AdminOrderController::class, 'complete'])->name('orders.complete');
        Route::post('orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('orders-export', [AdminOrderController::class, 'export'])->name('orders.export');
        Route::post('orders/bulk-update', [AdminOrderController::class, 'bulkUpdate'])->name('orders.bulk-update');

        // Review Routes
        Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index'); 
        Route::get('reviews/pending', [AdminReviewController::class, 'pending'])->name('reviews.pending');
        Route::post('reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
        Route::post('reviews/bulk-approve', [AdminReviewController::class, 'bulkApprove'])->name('reviews.bulk-approve');
        Route::post('reviews/bulk-reject', [AdminReviewController::class, 'bulkReject'])->name('reviews.bulk-reject');
    });
