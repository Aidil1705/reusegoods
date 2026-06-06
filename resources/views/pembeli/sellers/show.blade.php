@extends('layouts.pembeli')

@section('title', 'Profil Penjual - ' . $seller->name . ' - ReGoods')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <div class="mb-6 text-sm text-gray-600">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-green-600">Beranda</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Penjual: {{ $seller->name }}</span>
    </div>

    <!-- Seller Header -->
    <div class="bg-white rounded-2xl shadow p-8 mb-8">
        <div class="flex items-center gap-6 mb-6">
            <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                <span class="text-white font-bold text-3xl">{{ substr($seller->name, 0, 1) }}</span>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $seller->name }}</h1>
                <p class="text-gray-600">Penjual Terpercaya</p>
            </div>
        </div>

        <!-- Seller Stats -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-600 text-sm">Rating Toko</p>
                <p class="text-2xl font-bold text-yellow-500 mt-1">4.5 ⭐</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-600 text-sm">Produk Aktif</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $products->total() }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-600 text-sm">Penjualan</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">156 terjual</p>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div>
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Produk dari Toko Ini</h2>

        @if ($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
                        @php
                            $image = $product->primaryImage;
                        @endphp

                        <a href="{{ route('pembeli.products.show', $product->id) }}" class="block">
                            <img 
                                src="{{ $image ? asset('storage/' . $image->image_url) : asset('images/no-image.png') }}" 
                                class="w-full h-40 object-cover hover:scale-105 transition"
                                alt="{{ $product->name }}"
                            >
                        </a>

                        <div class="p-4">
                            <a href="{{ route('pembeli.products.show', $product->id) }}" class="hover:text-green-600">
                                <h3 class="font-semibold text-gray-800 line-clamp-2">
                                    {{ $product->name }}
                                </h3>
                            </a>

                            <p class="text-green-600 font-bold mt-2">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>

                            <div class="flex gap-2 mt-3">
                                <span class="text-xs bg-gray-200 px-2 py-1 rounded-full">
                                    {{ ucfirst($product->condition) }}
                                </span>
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                    Stok: {{ $product->stock }}
                                </span>
                            </div>

                            <div class="flex gap-2 mt-3">
                                <a href="{{ route('pembeli.products.show', $product->id) }}" class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-center text-sm transition">
                                    Lihat
                                </a>
                                <form action="{{ route('pembeli.cart.add') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg text-sm transition">
                                        🛒 Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="bg-gray-50 rounded-2xl p-12 text-center">
                <p class="text-gray-600 text-lg">Penjual ini belum memiliki produk</p>
            </div>
        @endif
    </div>
</div>
@endsection
