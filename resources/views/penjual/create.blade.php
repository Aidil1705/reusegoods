@extends('layouts.penjual-new')

@section('title', 'Tambah Produk')

@section('content')

<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Produk Baru</h1>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-lg border border-red-200">
            <p class="font-semibold mb-2">Error:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('penjual.produk.store') }}" method="post" enctype="multipart/form-data" class="bg-white rounded-2xl shadow p-6">
        @csrf
        
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Produk</label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="Masukkan judul produk" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
            <textarea name="description" placeholder="Jelaskan produk Anda" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none" rows="4">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Harga (Rp)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}" placeholder="0" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Stok</label>
                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" placeholder="1" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Produk</label>
            <input type="file" name="image" class="w-full border-2 border-dashed rounded-lg px-4 py-6 cursor-pointer hover:border-green-500" accept="image/*">
            <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Ukuran maks: 2MB</p>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition">Simpan Produk</button>
            <a href="{{ route('penjual.produk.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-medium transition">Batal</a>
        </div>
    </form>
</div>

@endsection
