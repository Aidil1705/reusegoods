<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\Pembeli\DashboardController;
use App\Http\Controllers\Pembeli\ProductController;
use App\Http\Controllers\Pembeli\SellerController;
use App\Http\Controllers\Pembeli\CartController;
use App\Http\Controllers\Pembeli\ProfileController;
use App\Http\Controllers\SellerApplicationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect('/pembeli/dashboard')
        : redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Pembeli Routes with Role Check
Route::middleware(['auth', 'role:pembeli'])->group(function () {
    Route::get('/pembeli/dashboard', [DashboardController::class, 'index'])->name('pembeli.dashboard');
    Route::get('/pembeli/products/{id}', [ProductController::class, 'show'])->name('pembeli.products.show');
    Route::get('/pembeli/sellers/{id}', [SellerController::class, 'show'])->name('pembeli.sellers.show');

    Route::prefix('pembeli/cart')->name('pembeli.cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::post('/{item}/update', [CartController::class, 'update'])->name('update');
        Route::delete('/{item}/remove', [CartController::class, 'remove'])->name('remove');
        Route::post('/clear', [CartController::class, 'clear'])->name('clear');
        Route::get('/count', [CartController::class, 'getCount'])->name('count');
    });

    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/unread-count', [ChatController::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/start', [ChatController::class, 'startChat'])->name('start');
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::post('/start', [ChatController::class, 'startChat'])->name('start');
        Route::get('/unread-count', [ChatController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
        Route::post('/{conversation}/send', [ChatController::class, 'sendMessage'])->name('send');
    });

    Route::prefix('pembeli/profile')->name('pembeli.profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/upload-ktp', [ProfileController::class, 'uploadKTP'])->name('upload-ktp');
    });
});

// Admin Routes
Route::prefix('admin')->middleware('auth:admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');

    Route::get('/products', [PageController::class, 'products'])->name('products.index');
    Route::get('/products/create', [PageController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [PageController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [PageController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [PageController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [PageController::class, 'destroyProduct'])->name('products.destroy');

    Route::get('/categories', [PageController::class, 'categories'])->name('categories.index');
    Route::get('/categories/create', [PageController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [PageController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [PageController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categories/{category}', [PageController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [PageController::class, 'destroyCategory'])->name('categories.destroy');

    Route::get('/orders', [PageController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{orderId}', [PageController::class, 'showOrder'])->name('orders.show');

    Route::get('/reports', [PageController::class, 'reports'])->name('reports.index');
    Route::get('/reports/show', [PageController::class, 'showReportDetail'])->name('reports.show');

    Route::get('/users', [PageController::class, 'users'])->name('users.index');
    Route::patch('/users/{user}/toggle-status', [PageController::class, 'toggleUserStatus'])->name('users.toggle-status');

    Route::get('/admins', [PageController::class, 'admins'])->name('admins.index');
    Route::get('/admins/create', [PageController::class, 'createAdmin'])->name('admins.create');
    Route::post('/admins', [PageController::class, 'storeAdmin'])->name('admins.store');
    Route::get('/admins/{user}/edit', [PageController::class, 'editAdmin'])->name('admins.edit');
    Route::put('/admins/{user}', [PageController::class, 'updateAdmin'])->name('admins.update');
    Route::delete('/admins/{user}', [PageController::class, 'destroyAdmin'])->name('admins.destroy');

    Route::get('/seller-applications', [SellerApplicationController::class, 'index'])->name('seller-applications.index');
    Route::patch('/seller-applications/{application}/approve', [SellerApplicationController::class, 'approve'])->name('seller-applications.approve');
    Route::post('/seller-applications/{application}/reject', [SellerApplicationController::class, 'reject'])->name('seller-applications.reject');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Penjual Routes with Role Check
Route::middleware(['auth', 'role:penjual'])->prefix('penjual')->name('penjual.')->group(function () {
    Route::get('/dashboard', [PenjualController::class, 'dashboard'])->name('dashboard');
    Route::resource('produk', PenjualController::class);
    Route::patch('produk/{produk}/toggle-active', [PenjualController::class, 'toggleActive'])->name('produk.toggle-active');
});
