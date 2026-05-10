<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
<<<<<<< HEAD

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
=======
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

>>>>>>> ba632d81e36ff1a3d09e2e21b0b8364b25ca53b8
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
<<<<<<< HEAD
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
=======
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
>>>>>>> ba632d81e36ff1a3d09e2e21b0b8364b25ca53b8
    }
}
