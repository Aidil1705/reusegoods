<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display cart items
     */
    public function index()
    {
        $user = auth()->user();
        $cart = $user->cart ?? null;
        
        if (!$cart) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        }

        $items = $cart->items()->with('product.images')->get();
        $total = $cart->getTotalPrice();

        return view('pembeli.cart.index', compact('cart', 'items', 'total'));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $product = Product::findOrFail($request->product_id);
        
        // Get or create cart
        $cart = $user->cart ?? Cart::create(['user_id' => $user->id]);

        // Check if product already in cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Update quantity
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity
            ]);
        } else {
            // Add new item
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->route('pembeli.cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::findOrFail($itemId);
        $item->update(['quantity' => $request->quantity]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('pembeli.cart.index')->with('success', 'Keranjang berhasil diperbarui');
    }

    /**
     * Remove item from cart
     */
    public function remove($itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $item->delete();

        return redirect()->route('pembeli.cart.index')->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $user = auth()->user();
        if ($user->cart) {
            $user->cart->items()->delete();
        }

        return redirect()->route('pembeli.cart.index')->with('success', 'Keranjang berhasil dikosongkan');
    }

    /**
     * Get cart count (for AJAX)
     */
    public function getCount()
    {
        $user = auth()->user();
        $cart = $user->cart;
        $count = $cart ? $cart->getTotalItems() : 0;

        return response()->json(['count' => $count]);
    }
}
