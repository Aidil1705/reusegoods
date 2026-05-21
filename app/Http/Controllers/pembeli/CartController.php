<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Add product to cart
    public function addToCart(Product $product)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Silakan login terlebih dahulu'], 401);
        }

        $user = Auth::user();

        if ($product->stock <= 0) {
            return response()->json(['status' => 'error', 'message' => 'Produk tidak tersedia'], 400);
        }

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
            ]);
        }

        $cart->total_price = $cart->items()->sum(DB::raw('quantity * price'));
        $cart->save();

        return response()->json(['status' => 'added', 'message' => 'Ditambahkan ke keranjang']);
    }

    // Remove item from cart
    public function removeFromCart(CartItem $cartItem)
    {
        $cart = $cartItem->cart;

        $cartItem->delete();

        $cart->total_price = $cart->items()->sum(DB::raw('quantity * price'));
        $cart->save();

        return response()->json(['status' => 'removed', 'message' => 'Dihapus dari keranjang']);
    }
}
