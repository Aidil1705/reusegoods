@extends('admin.layouts.app')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-slate-500">Detail laporan untuk periode terbaru.</p>
                <p class="text-2xl font-semibold text-slate-900">Ringkasan lengkap</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Kembali</a>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        @foreach($reportItems as $item)
        <div class="rounded-2xl bg-white border border-slate-100 p-5 shadow-sm">
            <p class="text-xs text-slate-500 uppercase tracking-[0.15em] mb-3">{{ $item['label'] }}</p>
            <p class="text-xl font-semibold text-slate-900">{{ $item['value'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Catatan Laporan</h2>
        <p class="text-sm leading-relaxed text-slate-600">Laporan ini memberikan gambaran ringkas tentang performa penjualan, produk terpopuler, dan tren pertumbuhan pengguna. Gunakan detail ini untuk mengambil keputusan inventaris dan promosi yang lebih tepat.</p>
    </div>
</div>
@endsection
