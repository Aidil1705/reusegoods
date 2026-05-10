@extends('layouts.penjual-new')

@section('title', 'Kelola Produk')

@section('content')

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

    @if($products->count())
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

        <div class="mt-4">{{ $products->links() }}</div>
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
