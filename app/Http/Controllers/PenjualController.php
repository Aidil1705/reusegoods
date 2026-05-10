<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $totalSales = $user->products()->where('status', 'sold')->count(); // Jumlah produk terjual
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
        $categories = Category::orderBy('name')->get();

        return view('penjual.create', compact('categories'));
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        $data['user_id'] = Auth::id() ?? 1;

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product = Product::create($data);

        // Create ProductImage record for pembeli dashboard
        if ($imagePath) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $imagePath,
                'is_primary' => 1,
            ]);
        }

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

        $categories = Category::orderBy('name')->get();

        return view('penjual.edit', ['product' => $produk, 'categories' => $categories]);
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);

        if ($request->hasFile('image')) {
            // Delete old images
            if ($produk->image) {
                Storage::disk('public')->delete($produk->image);
            }
            
            $produk->images()->delete();

            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;

            // Create new ProductImage record
            ProductImage::create([
                'product_id' => $produk->id,
                'image_url' => $imagePath,
                'is_primary' => 1,
            ]);
        }

        $produk->update($data);

        return redirect()->route('penjual.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $produk)
    {
        if ($produk->user_id !== (Auth::id() ?? 1)) {
            abort(403);
        }

        // Delete image file from storage
        if ($produk->image) {
            Storage::disk('public')->delete($produk->image);
        }

        // Delete ProductImage records
        $produk->images()->delete();

        $produk->delete();

        return redirect()->route('penjual.produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleActive(Product $produk)
    {
        if ($produk->user_id !== (Auth::id() ?? 1)) {
            abort(403);
        }

        $produk->update(['is_active' => !$produk->is_active]);

        $status = $produk->is_active ? 'aktif' : 'non-aktif';
        return redirect()->route('penjual.dashboard')->with('success', "Produk berhasil diubah menjadi {$status}.");
    }
}
