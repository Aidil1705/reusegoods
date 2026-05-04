@extends('admin.layouts.app')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-slate-500">ID Pesanan</p>
                <p class="text-xl font-semibold text-slate-900">{{ $order['id'] }}</p>
            </div>
            <div class="text-right">
                <p class="text-slate-500">Tanggal</p>
                <p class="text-base font-semibold text-slate-900">{{ $order['date'] }}</p>
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs text-slate-500 uppercase tracking-[0.15em] mb-2">Pembeli</p>
                <p class="text-sm font-semibold text-slate-900">{{ $order['buyer'] }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs text-slate-500 uppercase tracking-[0.15em] mb-2">Total</p>
                <p class="text-sm font-semibold text-slate-900">{{ $order['total'] }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs text-slate-500 uppercase tracking-[0.15em] mb-2">Status</p>
                <p class="text-sm font-semibold text-slate-900">{{ $order['status'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-slate-900">Item Pesanan</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-brand-600 hover:text-brand-700 font-semibold">Kembali ke daftar</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm divide-y divide-slate-200">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Nama Item</th>
                        <th class="px-6 py-3 font-semibold">Jumlah</th>
                        <th class="px-6 py-3 font-semibold">Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach($order['items'] as $item)
                    <tr>
                        <td class="px-6 py-4 text-slate-700">{{ $item['name'] }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $item['qty'] }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $item['price'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
