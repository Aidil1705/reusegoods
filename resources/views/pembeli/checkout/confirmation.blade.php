@extends('layouts.pembeli')

@section('title', 'Konfirmasi Pesanan - ReGoods')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow p-8 text-center mb-6">
        <div class="mb-6">
            <svg class="w-24 h-24 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-2">Pesanan Berhasil Dibuat!</h1>
        <p class="text-gray-600 mb-6">Terima kasih telah berbelanja di ReGoods</p>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-700">
                <strong>Nomor Pesanan:</strong> <span class="font-mono">{{ $order->order_number }}</span>
            </p>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-8">
            <div>
                <p class="text-gray-600 text-sm">Status</p>
                <p class="text-lg font-bold text-gray-800">
                    <span class="px-3 py-1 rounded-full text-white text-sm" style="background-color: #f59e0b;">
                        Menunggu Pembayaran
                    </span>
                </p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Total Pesanan</p>
                <p class="text-lg font-bold text-green-600">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Tanggal Pesanan</p>
                <p class="text-lg font-bold text-gray-800">{{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Rincian Pesanan</h2>
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200 last:border-0">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>
                            <p class="text-sm text-gray-600">Qty: {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <p class="font-bold text-green-600">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Summary -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
            <div class="flex justify-between mb-2">
                <span class="text-gray-700">Subtotal</span>
                <span class="font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            @if ($order->shipping_cost > 0)
                <div class="flex justify-between mb-2">
                    <span class="text-gray-700">Ongkir ({{ strtoupper($order->courier) }})</span>
                    <span class="font-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between border-t border-green-300 pt-2 mt-2">
                <span class="font-bold text-lg">Total Pembayaran</span>
                <span class="font-bold text-lg text-green-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 justify-center">
            <a href="{{ route('pembeli.dashboard') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-lg transition">
                Lanjut Belanja
            </a>
            <a href="#" class="border-2 border-green-500 text-green-500 hover:bg-green-50 font-bold py-3 px-8 rounded-lg transition">
                Lakukan Pembayaran
            </a>
        </div>

        <!-- Note -->
        <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm text-yellow-800">
                <strong>Catatan:</strong> Pesanan Anda akan diproses setelah pembayaran diterima. 
                Silakan lakukan pembayaran dalam 24 jam untuk menghindari pembatalan otomatis.
            </p>
        </div>
    </div>
</div>
@endsection
