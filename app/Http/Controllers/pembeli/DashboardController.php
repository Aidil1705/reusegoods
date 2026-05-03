<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::with('images')->latest()->get();

        return view('pembeli.dashboard', compact('products'));
    }
}