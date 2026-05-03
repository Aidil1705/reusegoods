@extends('layouts.pembeli')

@section('title', 'Dashboard Pembeli')

@section('content')
<div class="grid grid-cols-4 gap-6">

@foreach ($products as $product)
    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
        
        @php
            $image = $product->primaryImage;
        @endphp

        <img 
            src="{{ $image ? asset('storage/' . $image->image_url) : asset('images/no-image.png') }}" 
            class="w-full h-40 object-cover"
            alt="{{ $product->name }}"
        >

        <div class="p-4">
            <h3 class="font-semibold text-gray-800">
                {{ $product->name }}
            </h3>

            <p class="text-green-600 font-bold mt-1">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>

            <span class="text-xs bg-gray-200 px-2 py-1 rounded-full inline-block mt-2">
                {{ ucfirst($product->condition) }}
            </span>

            <button class="mt-3 w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg">
                Lihat Detail
            </button>
        </div>
    </div>
@endforeach

</div>
@endsection