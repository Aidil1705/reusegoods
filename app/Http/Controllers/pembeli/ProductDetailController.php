<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function show(Product $product)
    {
        $product->load(['images', 'category', 'user']);
        
        // Get related products from same seller
        $relatedProducts = Product::where('user_id', $product->user_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'aktif')
            ->take(4)
            ->get();
        
        return view('pembeli.product-detail', compact('product', 'relatedProducts'));
    }
}
