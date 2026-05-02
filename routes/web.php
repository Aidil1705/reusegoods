<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/pembeli/dashboard', function () {
    return view('pembeli.dashboard');
})->name('pembeli.dashboard');
