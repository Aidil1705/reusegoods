<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function dashboard()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'aktif')->count();
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalCategories = Category::count();
        $totalAdmins = DB::table('set_roles')
            ->join('roles', 'set_roles.role_id', '=', 'roles.id')
            ->where('roles.role_name', 'admin')
            ->count();

        $pendingOrders = collect([
            (object) [
                'product_image' => null,
                'product_name' => 'Sepeda Lipat',
                'buyer_name' => 'Andi',
                'created_at' => now()->subMinutes(5),
                'total_price' => 850000,
                'status' => 'Menunggu',
            ],
            (object) [
                'product_image' => null,
                'product_name' => 'Kulkas Mini',
                'buyer_name' => 'Rina',
                'created_at' => now()->subMinutes(18),
                'total_price' => 180000,
                'status' => 'Dikonfirmasi',
            ],
            (object) [
                'product_image' => null,
                'product_name' => 'Meja Belajar',
                'buyer_name' => 'Budi',
                'created_at' => now()->subHour(),
                'total_price' => 325000,
                'status' => 'Menunggu',
            ],
        ]);

        $recentUsers = User::latest()->take(4)->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'activeProducts',
            'totalUsers',
            'activeUsers',
            'totalCategories',
            'totalAdmins',
            'pendingOrders',
            'recentUsers'
        ));
    }

    public function products()
    {
        $products = Product::with(['primaryImage', 'category'])->latest()->get();

        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'condition' => 'required|in:baru,bekas',
            'description' => 'nullable|string',
        ]);

        Product::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'condition' => $validated['condition'],
            'status' => 'aktif',
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function editProduct(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $payload = [
            'name' => $request->filled('name') ? $request->input('name') : $product->name,
            'category_id' => $request->filled('category_id') ? $request->input('category_id') : $product->category_id,
            'price' => $request->filled('price') ? $request->input('price') : $product->price,
            'stock' => $request->filled('stock') ? $request->input('stock') : $product->stock,
            'condition' => $request->filled('condition') ? $request->input('condition') : $product->condition,
            'description' => $request->input('description', $product->description),
        ];

        $validated = validator($payload, [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'condition' => 'required|in:baru,bekas',
            'description' => 'nullable|string',
        ])->validate();

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'condition' => $validated['condition'],
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroyProduct(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function categories()
    {
        $categories = Category::withCount('products')
            ->orderByDesc('products_count')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    public function orders()
    {
        $orders = collect([
            ['id' => '#1023', 'buyer' => 'Andi', 'total' => 'Rp320.000', 'date' => 'Apr 28', 'status' => 'Menunggu', 'items' => [
                ['name' => 'Sepeda Lipat', 'qty' => 1, 'price' => 'Rp850.000'],
                ['name' => 'Kunci Sepeda', 'qty' => 1, 'price' => 'Rp75.000'],
            ]],
            ['id' => '#1024', 'buyer' => 'Rina', 'total' => 'Rp180.000', 'date' => 'Apr 29', 'status' => 'Dikonfirmasi', 'items' => [
                ['name' => 'Kulkas Mini', 'qty' => 1, 'price' => 'Rp180.000'],
            ]],
            ['id' => '#1025', 'buyer' => 'Budi', 'total' => 'Rp540.000', 'date' => 'Apr 30', 'status' => 'Dikirim', 'items' => [
                ['name' => 'Meja Belajar', 'qty' => 1, 'price' => 'Rp325.000'],
                ['name' => 'Lampu Meja', 'qty' => 1, 'price' => 'Rp95.000'],
            ]],
        ]);

        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder($orderId)
    {
        $orders = collect([
            ['id' => '#1023', 'buyer' => 'Andi', 'total' => 'Rp320.000', 'date' => 'Apr 28', 'status' => 'Menunggu', 'items' => [
                ['name' => 'Sepeda Lipat', 'qty' => 1, 'price' => 'Rp850.000'],
                ['name' => 'Kunci Sepeda', 'qty' => 1, 'price' => 'Rp75.000'],
            ]],
            ['id' => '#1024', 'buyer' => 'Rina', 'total' => 'Rp180.000', 'date' => 'Apr 29', 'status' => 'Dikonfirmasi', 'items' => [
                ['name' => 'Kulkas Mini', 'qty' => 1, 'price' => 'Rp180.000'],
            ]],
            ['id' => '#1025', 'buyer' => 'Budi', 'total' => 'Rp540.000', 'date' => 'Apr 30', 'status' => 'Dikirim', 'items' => [
                ['name' => 'Meja Belajar', 'qty' => 1, 'price' => 'Rp325.000'],
                ['name' => 'Lampu Meja', 'qty' => 1, 'price' => 'Rp95.000'],
            ]],
        ]);

        $order = $orders->firstWhere('id', $orderId);

        if (! $order) {
            abort(404);
        }

        return view('admin.orders.show', compact('order'));
    }

    public function reports()
    {
        $monthlyRevenue = Product::sum('price');
        $salesCount = Product::where('status', 'sold')->count();
        $weekOrders = Product::where('status', 'sold')->count();
        $reportRevenue = $monthlyRevenue;

        return view('admin.reports.index', compact('monthlyRevenue', 'salesCount', 'weekOrders', 'reportRevenue'));
    }

    public function showReportDetail()
    {
        $reportItems = collect([
            ['label' => 'Pendapatan Per Kategori', 'value' => 'Rp4.250.000'],
            ['label' => 'Produk Terlaris', 'value' => 'Sepeda Lipat'],
            ['label' => 'Pertumbuhan Pengguna', 'value' => '+12%'],
            ['label' => 'Rata-rata Pesanan', 'value' => 'Rp215.000'],
        ]);

        return view('admin.reports.show', compact('reportItems'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ];

        if (! empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroyUser(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function createAdmin()
    {
        return view('admin.admins.create');
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_active' => true,
        ]);

        $adminRoleId = DB::table('roles')->where('role_name', 'admin')->value('id');

        if (! $adminRoleId) {
            $adminRoleId = DB::table('roles')->insertGetId([ 'role_name' => 'admin' ]);
        }

        DB::table('set_roles')->insert([
            'user_id' => $user->id,
            'role_id' => $adminRoleId,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function editAdmin(User $user)
    {
        return view('admin.admins.edit', compact('user'));
    }

    public function updateAdmin(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ];

        if (! empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        $user->update($data);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroyAdmin(User $user)
    {
        DB::table('set_roles')->where('user_id', $user->id)->delete();
        $user->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dihapus.');
    }

    public function users()
    {
        $users = User::latest()->get();

        return view('admin.users.index', compact('users'));
    }

    public function admins()
    {
        $admins = DB::table('users')
            ->join('set_roles', 'users.id', '=', 'set_roles.user_id')
            ->join('roles', 'set_roles.role_id', '=', 'roles.id')
            ->where('roles.role_name', 'admin')
            ->select('users.*', 'roles.role_name')
            ->get();

        return view('admin.admins.index', compact('admins'));
    }
}
