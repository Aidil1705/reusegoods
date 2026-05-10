@extends('admin.layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')
<div class="grid gap-4 mb-6 xl:grid-cols-3">
    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <p class="text-sm text-slate-400 mb-2">Pendapatan Bulanan</p>
        <p class="text-3xl font-bold text-slate-900">Rp{{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
        <p class="text-sm text-slate-500">Total pendapatan dari data produk</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <p class="text-sm text-slate-400 mb-2">Transaksi Selesai</p>
        <p class="text-3xl font-bold text-slate-900">{{ $salesCount }}</p>
        <p class="text-sm text-slate-500">Produk terjual tercatat</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <p class="text-sm text-slate-400 mb-2">Barang Tersedia</p>
        <p class="text-3xl font-bold text-slate-900">{{ number_format($reportRevenue, 0, ',', '.') }}</p>
        <p class="text-sm text-slate-500">Jumlah value barang saat ini</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-100 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-slate-800">Ringkasan Laporan Mingguan</h2>
        <a href="{{ route('admin.reports.show') }}" class="text-brand-600 hover:text-brand-700 font-semibold">Lihat detail</a>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="rounded-2xl bg-slate-50 p-4">
            <p class="text-xs text-slate-500 uppercase tracking-[0.15em] mb-2">Pendapatan</p>
            <p class="text-2xl font-semibold text-slate-900">Rp{{ number_format($monthlyRevenue / 4, 0, ',', '.') }}</p>
            <p class="text-sm text-slate-500">Perkiraan per minggu</p>
        </div>
        <div class="rounded-2xl bg-slate-50 p-4">
            <p class="text-xs text-slate-500 uppercase tracking-[0.15em] mb-2">Pesanan</p>
            <p class="text-2xl font-semibold text-slate-900">{{ $weekOrders }}</p>
            <p class="text-sm text-slate-500">Transaksi terjual tercatat</p>
        </div>
    </div>
</div>
@endsection
