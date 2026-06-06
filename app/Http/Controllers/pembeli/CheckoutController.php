<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show checkout page with selected items
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Get selected cart item IDs from query params or session
        $selectedItemIds = $request->query('items', []);
        $buyNowProduct = $request->query('product_id', null);
        $buyNowQuantity = (int) $request->query('quantity', 1);

        if ($buyNowProduct) {
            // Buy now - single product
            $product = Product::findOrFail($buyNowProduct);
            $image = $product->primaryImage ? $product->primaryImage->image_url : null;
            
            $items = [
                [
                    'id' => null,
                    'product_id' => $product->id,
                    'quantity' => $buyNowQuantity,
                    'name' => $product->name,
                    'price' => $product->price,
                    'seller' => $product->user->name,
                    'image' => $image,
                ]
            ];
            $isBuyNow = true;
            $subtotal = $product->price * $buyNowQuantity;
        } else {
            // From cart - multiple items
            if (empty($selectedItemIds)) {
                return redirect()->route('pembeli.cart.index')
                    ->with('error', 'Silakan pilih produk untuk checkout');
            }

            $cartItems = CartItem::whereIn('id', $selectedItemIds)
                ->with('product.images', 'product.user')
                ->get();
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('pembeli.cart.index')
                    ->with('error', 'Produk tidak ditemukan');
            }

            $items = $cartItems->map(function ($item) {
                $image = $item->product->primaryImage ? $item->product->primaryImage->image_url : null;
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'seller' => $item->product->user->name,
                    'image' => $image,
                ];
            })->toArray();

            $isBuyNow = false;
            $subtotal = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
        }

        $shippingCost = 0;
        
        return view('pembeli.checkout.index-livewire', [
            'items' => $items,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'isBuyNow' => $isBuyNow,
        ]);
    }

    /**
     * Process checkout
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'nullable|array',
            'items.*' => 'nullable|integer',
            'address' => 'required|string|min:10',
            'courier' => 'nullable|string',
            'shipping_cost' => 'nullable|integer|min:0',
            'destination_subdistrict_id' => 'nullable|string',
            'courier_service' => 'nullable|string',
            'product_id' => 'nullable|integer',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $user = auth()->user();
        $cart = $user->cart;

        // Get shipping cost from form (passed by Livewire)
        $shippingCost = (int) $request->input('shipping_cost', 0);
        $courier = $request->input('courier', 'jne');
        $courierService = $request->input('courier_service', null);

        // Determine if this is buy now or cart checkout
        $isBuyNow = $request->has('product_id') && $request->product_id;

        if ($isBuyNow) {
            // Buy now - single product
            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity ?? 1;

            // Validate stock
            if ($quantity > $product->stock) {
                return back()->with('error', 'Stok produk tidak cukup');
            }

            $subtotal = $product->price * $quantity;
            $total = $subtotal + $shippingCost;

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()) . '-' . date('YmdHis'),
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount' => 0,
                'total' => $total,
                'status' => 'pending',
                'courier' => $courier,
                'courier_service' => $courierService,
                'destination_address' => $request->address,
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
                'subtotal' => $subtotal,
            ]);
        } else {
            // Cart checkout
            if (empty($request->items)) {
                return back()->with('error', 'Silakan pilih produk untuk checkout');
            }

            // Get cart items
            $cartItems = CartItem::whereIn('id', $request->items)
                ->with('product')
                ->get();

            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Produk tidak ditemukan');
            }

            // Calculate subtotal from selected items
            $subtotal = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            $total = $subtotal + $shippingCost;

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()) . '-' . date('YmdHis'),
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount' => 0,
                'total' => $total,
                'status' => 'pending',
                'courier' => $courier,
                'courier_service' => $courierService,
                'destination_address' => $request->address,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'subtotal' => $cartItem->product->price * $cartItem->quantity,
                ]);
            }

            // Remove items from cart
            CartItem::whereIn('id', $request->items)->delete();
        }

        return redirect()->route('pembeli.checkout.confirmation', $order->id)
            ->with('success', 'Order berhasil dibuat. Silakan lakukan pembayaran.');
    }

    /**
     * Show order confirmation
     */
    public function confirmation($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);

        // Verify order belongs to user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('pembeli.checkout.confirmation', compact('order'));
    }

    /**
     * Buy now - create order from single product
     */
    public function buyNow(Product $product, Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        $user = auth()->user();

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-' . strtoupper(uniqid()) . '-' . date('YmdHis'),
            'subtotal' => $product->price * $request->quantity,
            'shipping_cost' => 0,
            'discount' => 0,
            'total' => $product->price * $request->quantity,
            'status' => 'pending',
        ]);

        // Create order item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price,
            'subtotal' => $product->price * $request->quantity,
        ]);

        return redirect()->route('pembeli.checkout.index', [
            'order' => $order->id
        ])->with('success', 'Silakan masukkan alamat pengiriman dan lakukan pembayaran.');
    }
}
