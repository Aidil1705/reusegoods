@extends('layouts.pembeli')

@section('title', 'Dashboard Pembeli')

@section('content')
<!-- Search and Filters -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form action="{{ route('pembeli.dashboard') }}" method="GET" class="space-y-4">
        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
            <input type="text" name="search" placeholder="Cari nama produk..."
                value="{{ request('search') }}"
                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none">
        </div>

        <!-- Filters Row -->
        <div class="grid grid-cols-3 gap-4">
            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Condition Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi</label>
                <select name="condition" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none">
                    <option value="">Semua Kondisi</option>
                    <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>Baru</option>
                    <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>Bekas</option>
                </select>
            </div>

            <!-- Price Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Maksimal</label>
                <input type="number" name="max_price" placeholder="Harga maksimal..."
                    value="{{ request('max_price') }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none">
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-2">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium">
                Cari
            </button>
            <a href="{{ route('pembeli.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-medium">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Products Grid -->
<div class="grid grid-cols-4 gap-6">

@foreach ($products as $product)
    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
        
        @php
            $image = $product->primaryImage;
        @endphp

        <img 
            src="{{ $image ? asset('storage/' . $image->image_url) : asset('images/no-image.png') }}" 
            class="w-full h-40 object-cover"
            alt="{{ $product->name }}"
        >

        <div class="p-4">
            <h3 class="font-semibold text-gray-800">
                {{ $product->name }}
            </h3>

            <p class="text-green-600 font-bold mt-1">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>

            <span class="text-xs bg-gray-200 px-2 py-1 rounded-full inline-block mt-2">
                {{ ucfirst($product->condition) }}
            </span>

            <a href="{{ route('pembeli.products.show', $product->id) }}" class="mt-3 w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg inline-block text-center">
                Lihat Detail
            </a>
            </a>
        </div>
    </div>
@endforeach

</div>
@endsection