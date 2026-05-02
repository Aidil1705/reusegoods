@extends('layouts.pembeli')

@section('title', 'Dashboard')

@section('content')

<!-- Category -->
<div class="flex gap-3 mb-6">
    <button class="bg-green-500 text-white px-4 py-1 rounded-full">Semua</button>
    <button class="bg-gray-200 px-4 py-1 rounded-full">Elektronik</button>
    <button class="bg-gray-200 px-4 py-1 rounded-full">Fashion</button>
    <button class="bg-gray-200 px-4 py-1 rounded-full">Musik</button>
    <button class="bg-gray-200 px-4 py-1 rounded-full">Rumah</button>
</div>

<!-- Products -->
<div class="grid grid-cols-4 gap-6">

    @for ($i = 0; $i < 8; $i++)
    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
        
        <img src="https://picsum.photos/300/200?random={{ $i }}" class="w-full h-40 object-cover">

        <div class="p-4">
            <h3 class="font-semibold text-gray-800">Nama Produk</h3>
            <p class="text-green-600 font-bold mt-1">Rp 500.000</p>

            <span class="text-xs bg-gray-200 px-2 py-1 rounded-full inline-block mt-2">
                Like New
            </span>

            <button class="mt-3 w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg">
                Lihat Detail
            </button>
        </div>
    </div>
    @endfor

</div>

@endsection