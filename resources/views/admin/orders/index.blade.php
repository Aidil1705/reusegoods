@extends('admin.layouts.app')

@section('title', 'Pesanan')
@section('page-title', 'Pesanan')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-slate-500">Tinjau pesanan terbaru dan kelola status pengiriman.</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Segarkan
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="min-w-full text-left text-sm divide-y divide-slate-200">
        <thead class="bg-slate-50 text-slate-600">
            <tr>
                <th class="px-6 py-3 font-semibold">ID Pesanan</th>
                <th class="px-6 py-3 font-semibold">Pembeli</th>
                <th class="px-6 py-3 font-semibold">Total</th>
                <th class="px-6 py-3 font-semibold">Tanggal</th>
                <th class="px-6 py-3 font-semibold">Status</th>
                <th class="px-6 py-3 font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 bg-white">
            @forelse($orders as $order)
            <tr>
                <td class="px-6 py-4 text-slate-700 font-medium">{{ $order['id'] }}</td>
                <td class="px-6 py-4 text-slate-500">{{ $order['buyer'] }}</td>
                <td class="px-6 py-4 text-slate-500">{{ $order['total'] }}</td>
                <td class="px-6 py-4 text-slate-500">{{ $order['date'] }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-[11px] font-semibold {{ $order['status'] === 'Menunggu' ? 'bg-yellow-50 text-yellow-600' : ($order['status'] === 'Dikirim' ? 'bg-green-50 text-green-600' : 'bg-blue-50 text-blue-600') }}">
                        {{ $order['status'] }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.orders.show', $order['id']) }}" class="text-brand-600 hover:text-brand-700 font-semibold">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada pesanan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
