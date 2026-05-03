<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Pembeli\DashboardController;
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
    ->middleware('auth')
    ->name('pembeli.dashboard');
