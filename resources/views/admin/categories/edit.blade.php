@extends('admin.layouts.app')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100" placeholder="Masukkan nama kategori">
                @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.categories.index') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white hover:bg-brand-700">Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection
