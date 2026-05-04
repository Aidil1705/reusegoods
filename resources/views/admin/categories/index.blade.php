@extends('admin.layouts.app')

@section('title', 'Kategori')
@section('page-title', 'Kategori')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-slate-500">Kelola kategori agar pengguna dapat mencari barang dengan mudah.</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kategori
    </a>
</div>

<div class="grid gap-4">
    @forelse($categories as $category)
    <div class="bg-white rounded-2xl border border-slate-100 p-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-base font-semibold text-slate-800">{{ $category->name }}</p>
            <p class="text-sm text-slate-500">{{ $category->products_count }} barang tersedia</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.categories.edit', $category) }}" class="text-brand-600 hover:text-brand-700 font-semibold">Edit</a>
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kategori ini? Semua barang di kategori ini juga akan terhapus.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-700 font-semibold">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-slate-100 p-5 text-slate-500">Belum ada kategori.</div>
    @endforelse
</div>
@endsection
