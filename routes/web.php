<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenjualController;
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

Route::get('/pembeli/dashboard', function () {
    return view('pembeli.dashboard');
})->middleware('auth')->name('pembeli.dashboard');

Route::prefix('penjual')->middleware('auth')->name('penjual.')->group(function () {
    Route::get('/dashboard', [PenjualController::class, 'dashboard'])->name('dashboard');
    Route::resource('produk', PenjualController::class);
});
