@extends('admin.layouts.app')

@section('title', 'Kelola Barang')
@section('page-title', 'Kelola Barang')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-slate-500">Halaman admin untuk melihat dan mengelola semua barang.</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Barang
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="min-w-full text-left text-sm divide-y divide-slate-200">
        <thead class="bg-slate-50 text-slate-600">
            <tr>
                <th class="px-6 py-3 font-semibold">Nama Barang</th>
                <th class="px-6 py-3 font-semibold">Kategori</th>
                <th class="px-6 py-3 font-semibold">Harga</th>
                <th class="px-6 py-3 font-semibold">Stok</th>
                <th class="px-6 py-3 font-semibold">Status</th>
                <th class="px-6 py-3 font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 bg-white">
            @forelse($products as $product)
            <tr>
                <td class="px-6 py-4 text-slate-700 font-medium">{{ $product->name }}</td>
                <td class="px-6 py-4 text-slate-500">{{ $product->category->name ?? 'Tidak Diketahui' }}</td>
                <td class="px-6 py-4 text-slate-500">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-slate-500">{{ $product->stock }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-[11px] font-semibold {{ $product->status === 'aktif' ? 'bg-green-50 text-green-600' : 'bg-slate-100 text-slate-500' }}">
                        {{ ucfirst($product->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('admin.products.edit', $product) }}" class="text-brand-600 hover:text-brand-700 font-semibold">Edit</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus produk ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-700 font-semibold">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada barang.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
