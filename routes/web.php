<?php

use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\Pembeli\DashboardController;
use App\Http\Controllers\Pembeli\ProductDetailController;
use App\Http\Controllers\Pembeli\WishlistController;
use App\Http\Controllers\Pembeli\CartController;
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

Route::get('/pembeli/dashboard', [DashboardController::class, 'index'])
    ->name('pembeli.dashboard');

Route::get('/pembeli/produk/{product}', [ProductDetailController::class, 'show'])
    ->name('pembeli.product.show');

Route::middleware('auth')->group(function () {
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::post('/{product}/toggle', [WishlistController::class, 'toggle'])->name('toggle');
        Route::get('/{product}/check', [WishlistController::class, 'isInWishlist'])->name('check');
    });

    Route::prefix('cart')->name('cart.')->group(function () {
        Route::post('/{product}/add', [CartController::class, 'addToCart'])->name('add');
        Route::delete('/{cartItem}/remove', [CartController::class, 'removeFromCart'])->name('remove');
    });

    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/unread-count', [ChatController::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/start', [ChatController::class, 'startChat'])->name('start');
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
        Route::post('/{conversation}/send', [ChatController::class, 'sendMessage'])->name('send');
        Route::get('/{conversation}/poll', [ChatController::class, 'pollMessages'])->name('poll');
    });
});

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
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
    Route::get('/users/create', [PageController::class, 'createUser'])->name('users.create');
    Route::post('/users', [PageController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [PageController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [PageController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [PageController::class, 'destroyUser'])->name('users.destroy');

    Route::get('/admins', [PageController::class, 'admins'])->name('admins.index');
    Route::get('/admins/create', [PageController::class, 'createAdmin'])->name('admins.create');
    Route::post('/admins', [PageController::class, 'storeAdmin'])->name('admins.store');
    Route::get('/admins/{user}/edit', [PageController::class, 'editAdmin'])->name('admins.edit');
    Route::put('/admins/{user}', [PageController::class, 'updateAdmin'])->name('admins.update');
    Route::delete('/admins/{user}', [PageController::class, 'destroyAdmin'])->name('admins.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('penjual')->name('penjual.')->group(function () {
    Route::get('/dashboard', [PenjualController::class, 'dashboard'])->name('dashboard');
    Route::resource('produk', PenjualController::class);
});
