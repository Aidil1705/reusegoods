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
            <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10v2H7V2m11 4v10h2V6h-2m-2-4H8v2h8V2M4 6h14v12H4V6m2 2v8h10V8H6z"/></svg>
        </div>
    </div>

    <!-- Total Penjualan -->
    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Penjualan</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalSales ?? 0 }}</p>
            </div>
            <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2m1 15h-2v-2h2v2m0-4h-2V7h2v6z"/></svg>
        </div>
    </div>

    <!-- Rating Toko -->
    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Rating Toko</p>
                <div class="flex items-center gap-1 mt-2">
                    <p class="text-2xl font-bold text-gray-800">{{ $storeRating ?? '4.5' }}</p>
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2l-2.81 6.63L2 9.24l5.46 4.73L5.82 21z"/></svg>
                </div>
            </div>
            <svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8m3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5m-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11m3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5"/></svg>
        </div>
    </div>

    <!-- Pesanan Menunggu -->
    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Pesanan Menunggu</p>
                <p class="text-3xl font-bold text-orange-600 mt-2">{{ $pendingOrders ?? 0 }}</p>
            </div>
            <svg class="w-8 h-8 text-orange-500" fill="currentColor" viewBox="0 0 24 24"><path d="M11 17h2v-6h-2v6zm1-15C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-1.1 0-2 .9-2 2h2V5z"/></svg>
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
