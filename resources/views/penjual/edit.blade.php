@extends('layouts.penjual-new')

@section('title', 'Edit Produk')

@section('content')

<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Produk</h1>

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

    <form action="{{ route('penjual.produk.update', $product) }}" method="post" enctype="multipart/form-data" class="bg-white rounded-2xl shadow p-6">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" placeholder="Masukkan nama produk" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
            <select name="category_id" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none">
                <option value="">Pilih kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
            <textarea name="description" placeholder="Jelaskan produk Anda" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none" rows="4">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Harga (Rp)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" placeholder="0" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" placeholder="1" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Kondisi</label>
            <select name="condition" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none">
                <option value="">Pilih kondisi</option>
                <option value="baru" {{ old('condition', $product->condition) == 'baru' ? 'selected' : '' }}>Baru</option>
                <option value="bekas" {{ old('condition', $product->condition) == 'bekas' ? 'selected' : '' }}>Bekas</option>
            </select>
        </div>

        @if($product->image)
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Saat Ini</label>
            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded-lg">
        </div>
        @endif

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Ganti Gambar Produk</label>
            <input type="file" name="image" class="w-full border-2 border-dashed rounded-lg px-4 py-6 cursor-pointer hover:border-green-500" accept="image/*">
            <p class="text-xs text-gray-500 mt-2">Format: JPG, JPEG, PNG, WEBP. Ukuran maks: 2MB (Kosongkan jika tidak ingin mengganti)</p>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition">Perbarui Produk</button>
            <a href="{{ route('penjual.produk.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-medium transition">Batal</a>
        </div>
    </form>
</div>

@endsection
