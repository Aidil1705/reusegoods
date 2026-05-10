@extends('layouts.penjual-new')

@section('title', 'Dashboard Penjual')

@section('content')

<!-- Dashboard Header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Penjual</h1>
    <p class="text-gray-500 text-sm">{{ now()->format('l, d F Y') }}</p>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-4 gap-6 mb-8">
    <!-- Total Produk -->
    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Produk</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalProducts ?? 0 }}</p>
            </div>
            <div class="text-3xl">📦</div>
        </div>
    </div>

    <!-- Total Penjualan -->
    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Penjualan</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalSales ?? 0 }}</p>
            </div>
            <div class="text-3xl">💰</div>
        </div>
    </div>

    <!-- Rating Toko -->
    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Rating Toko</p>
                <div class="flex items-center gap-1 mt-2">
                    <p class="text-2xl font-bold text-gray-800">{{ $storeRating ?? '4.5' }}</p>
                    <span class="text-lg">⭐</span>
                </div>
            </div>
            <div class="text-3xl">🏪</div>
        </div>
    </div>

    <!-- Pesanan Menunggu -->
    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Pesanan Menunggu</p>
                <p class="text-3xl font-bold text-orange-600 mt-2">{{ $pendingOrders ?? 0 }}</p>
            </div>
            <div class="text-3xl">⏳</div>
        </div>
    </div>
</div>

<!-- Manage Products Section -->
<div class="bg-white rounded-2xl shadow p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-bold text-gray-800">Kelola Produk Saya</h2>
        <a href="{{ route('penjual.produk.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition">
            + Tambah Produk
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif

    @if(isset($products) && $products->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama Produk</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Harga</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Stok</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800 font-medium">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-gray-600">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $product->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('penjual.produk.edit', $product) }}" class="px-3 py-1 text-sm bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">Edit</a>
                            <form action="{{ route('penjual.produk.destroy', $product) }}" method="post" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(isset($products) && method_exists($products, 'links'))
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg mb-4">Tidak ada produk</p>
            <a href="{{ route('penjual.produk.create') }}" class="inline-block bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition">
                Buat Produk Pertama
            </a>
        </div>
    @endif
</div>

@endsection
