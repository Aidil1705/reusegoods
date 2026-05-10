<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjualController extends Controller
{
    public function dashboard()
    {
        /** @var User $user */
        $user = Auth::user() ?? User::first();
        
        if (!$user) {
            return view('penjual.dashboard', [
                'totalProducts' => 0,
                'totalSales' => 0,
                'storeRating' => 0,
                'pendingOrders' => 0,
                'products' => collect([]),
            ]);
        }
        
        $totalProducts = $user->products()->count();
        $totalSales = $user->products()->sum('quantity'); // Jumlah produk terjual
        $storeRating = 4.5; // Placeholder
        $pendingOrders = 0; // Placeholder
        $products = $user->products()->latest()->paginate(5);

        return view('penjual.dashboard', compact('totalProducts', 'totalSales', 'storeRating', 'pendingOrders', 'products'));
    }

    public function index()
    {
        /** @var User $user */
        $user = Auth::user() ?? User::first();
        
        if (!$user) {
            return view('penjual.index', ['products' => collect([])]);
        }
        
        $products = $user->products()->latest()->paginate(10);
        return view('penjual.index', compact('products'));
    }

    public function create()
    {
        return view('penjual.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'condition' => 'required|in:baru,bekas',
            'category_id' => 'required|integer',
        ]);

        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        $data['user_id'] = Auth::id() ?? 1;

        Product::create($data);

        return redirect()->route('penjual.produk.index')->with('success', 'Produk berhasil dibuat.');
    }

    public function show(Product $produk)
    {
        if ($produk->user_id !== (Auth::id() ?? 1)) {
            abort(403);
        }

        return view('penjual.show', ['product' => $produk]);
    }

    public function edit(Product $produk)
    {
        if ($produk->user_id !== (Auth::id() ?? 1)) {
            abort(403);
        }

        return view('penjual.edit', ['product' => $produk]);
    }

    public function update(Request $request, Product $produk)
    {
        if ($produk->user_id !== (Auth::id() ?? 1)) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'condition' => 'required|in:baru,bekas',
            'category_id' => 'required|integer',
        ]);

        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);

        $produk->update($data);

        return redirect()->route('penjual.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $produk)
    {
        if ($produk->user_id !== (Auth::id() ?? 1)) {
            abort(403);
        }

        $produk->delete();

        return redirect()->route('penjual.produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
