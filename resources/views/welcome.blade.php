{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="text-center py-20 bg-blue-500 text-white">
    <h2 class="text-4xl font-bold mb-4">Selamat Datang di MyShop</h2>
    <p class="text-lg mb-6">Temukan berbagai produk terbaik dengan harga terjangkau</p>
    <a href="#" class="bg-white text-blue-500 px-6 py-3 rounded-full font-semibold hover:bg-gray-200">
        Belanja Sekarang
    </a>
</section>

<!-- Produk Dummy -->
<section class="py-16 px-8">
    <h3 class="text-2xl font-bold text-center mb-10">Produk Unggulan</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1 -->
        <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
            <img src="https://via.placeholder.com/300" class="rounded-lg mb-4">
            <h4 class="text-lg font-semibold">Produk 1</h4>
            <p class="text-gray-500">Rp 100.000</p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
            <img src="https://via.placeholder.com/300" class="rounded-lg mb-4">
            <h4 class="text-lg font-semibold">Produk 2</h4>
            <p class="text-gray-500">Rp 150.000</p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
            <img src="https://via.placeholder.com/300" class="rounded-lg mb-4">
            <h4 class="text-lg font-semibold">Produk 3</h4>
            <p class="text-gray-500">Rp 200.000</p>
        </div>
    </div>
</section>

@endsection