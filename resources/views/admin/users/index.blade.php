@extends('admin.layouts.app')

@section('title', 'Pengguna')
@section('page-title', 'Pengguna')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-slate-500">Kelola pengguna terdaftar di platform Anda.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Pengguna
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="min-w-full text-left text-sm divide-y divide-slate-200">
        <thead class="bg-slate-50 text-slate-600">
            <tr>
                <th class="px-6 py-3 font-semibold">Nama</th>
                <th class="px-6 py-3 font-semibold">Email</th>
                <th class="px-6 py-3 font-semibold">Role</th>
                <th class="px-6 py-3 font-semibold">Status</th>
                <th class="px-6 py-3 font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 bg-white">
            @forelse($users as $user)
            <tr>
                <td class="px-6 py-4 text-slate-700 font-medium">{{ $user->name }}</td>
                <td class="px-6 py-4 text-slate-500">{{ $user->email }}</td>
                <td class="px-6 py-4 text-slate-500">Pengguna</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-[11px] font-semibold {{ $user->is_active ? 'bg-green-50 text-green-600' : 'bg-slate-100 text-slate-500' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right space-x-3">
                    <a href="{{ route('admin.users.edit', $user) }}" class="text-brand-600 hover:text-brand-700 font-semibold">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pengguna ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-700 font-semibold">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada pengguna.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
