@extends('admin.layouts.app')

@section('title', 'Tambah Admin')
@section('page-title', 'Tambah Admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
    <form action="{{ route('admin.admins.store') }}" method="POST">
        @csrf

        <div class="grid gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100" placeholder="Contoh: Annisa Putri">
                @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100" placeholder="contoh@gmail.com">
                @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Password</label>
                    <input type="password" name="password" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100" placeholder="Minimal 6 karakter">
                    @error('password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100" placeholder="Ulangi password">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Nomor Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100" placeholder="0812xxxxxx">
                @error('phone')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Alamat</label>
                <textarea name="address" rows="4" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100" placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                @error('address')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.admins.index') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white hover:bg-brand-700">Simpan Admin</button>
            </div>
        </div>
    </form>
</div>
@endsection
