@extends('layouts.penjual-new')

@section('title', $product->title)

@section('content')

<div class="max-w-3xl">
    <a href="{{ route('penjual.produk.index') }}" class="text-green-600 hover:text-green-700 text-sm font-medium mb-4 inline-block">← Kembali</a>

    <div class="bg-white rounded-2xl shadow p-6">
        <div class="grid grid-cols-2 gap-6">
            <!-- Gambar -->
            <div>
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->title }}" class="w-full h-auto object-cover rounded-lg">
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-gray-400">Tidak ada gambar</span>
                    </div>
                @endif
            </div>

            <!-- Info Produk -->
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $product->title }}</h1>
                
                <div class="mb-6">
                    <p class="text-3xl font-bold text-green-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 font-medium">Stok Tersedia</p>
                    <p class="text-xl font-semibold text-gray-800">{{ $product->quantity }} unit</p>
                </div>

                <div class="mb-6 pb-6 border-b">
                    <p class="text-sm text-gray-600 font-medium mb-2">Deskripsi</p>
                    <p class="text-gray-700 leading-relaxed">{{ $product->description ?? 'Tidak ada deskripsi' }}</p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('penjual.produk.edit', $product) }}" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium text-center transition">
                        ✏️ Edit
                    </a>
                    <form action="{{ route('penjual.produk.destroy', $product) }}" method="post" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition" onclick="return confirm('Hapus produk ini? Tindakan ini tidak dapat dibatalkan.')">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
