@extends('layouts.pembeli')

@section('title', $product->name . ' - ReGoods')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <div class="mb-6 text-sm text-gray-600">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-green-600">Beranda</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">{{ $product->name }}</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Left Column: Image Gallery -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <!-- Main Image Carousel -->
                <div x-data="imageCarousel()" class="relative bg-gray-200">
                    <div class="w-full h-96 md:h-[500px] bg-gray-100 flex items-center justify-center overflow-hidden relative">
                        @if ($product->images && count($product->images) > 0)
                            @foreach ($product->images as $index => $image)
                                <img 
                                    src="{{ asset('storage/' . $image->image_url) }}" 
                                    alt="Product image {{ $index + 1 }}"
                                    class="w-full h-full object-cover transition-opacity duration-300"
                                    :class="currentIndex === {{ $index }} ? 'opacity-100' : 'opacity-0 absolute'"
                                >
                            @endforeach
                        @else
                            <img 
                                src="{{ asset('images/no-image.png') }}" 
                                alt="No image"
                                class="w-full h-full object-cover"
                            >
                        @endif
                    </div>

                    <!-- Navigation Buttons -->
                    @if ($product->images && count($product->images) > 1)
                        <button 
                            @click="prev()" 
                            class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow transition"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button 
                            @click="next()" 
                            class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow transition"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>

                        <!-- Indicator -->
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white bg-black/50 px-3 py-1 rounded-full text-sm">
                            <span x-text="currentIndex + 1"></span> / <span x-text="totalImages"></span>
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Gallery -->
                @if ($product->images && count($product->images) > 1)
                    <div class="p-4 border-t border-gray-200">
                        <div class="flex gap-2 overflow-x-auto" x-data="imageCarousel()">
                            @foreach ($product->images as $index => $image)
                                <button 
                                    @click="currentIndex = {{ $index }}"
                                    class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 transition"
                                    :class="currentIndex === {{ $index }} ? 'border-green-500' : 'border-gray-300'"
                                >
                                    <img 
                                        src="{{ asset('storage/' . $image->image_url) }}" 
                                        alt="Thumbnail {{ $index + 1 }}"
                                        class="w-full h-full object-cover"
                                    >
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Product Description -->
            <div class="mt-8 bg-white rounded-2xl shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Deskripsi Produk</h2>
                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">
                    {{ $product->description ?? 'Tidak ada deskripsi' }}
                </p>
            </div>

            <!-- Related Products -->
            @if ($relatedProducts->count() > 0)
                <div class="mt-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Produk Terkait</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach ($relatedProducts as $related)
                            <a href="{{ route('pembeli.products.show', $related->id) }}" class="group">
                                <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                                    <div class="h-40 bg-gray-200 overflow-hidden">
                                        @php
                                            $relatedImage = $related->primaryImage;
                                        @endphp
                                        <img 
                                            src="{{ $relatedImage ? asset('storage/' . $relatedImage->image_url) : asset('images/no-image.png') }}" 
                                            alt="{{ $related->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition"
                                        >
                                    </div>
                                    <div class="p-3">
                                        <h3 class="font-semibold text-sm text-gray-800 line-clamp-2">
                                            {{ $related->name }}
                                        </h3>
                                        <p class="text-green-600 font-bold text-sm mt-1">
                                            Rp {{ number_format($related->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Product Info & Actions -->
        <div>
            <!-- Basic Info -->
            <div class="bg-white rounded-2xl shadow p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>

                <!-- Condition Badge -->
                <div class="mb-4">
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold 
                        {{ $product->condition === 'baru' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($product->condition) }}
                    </span>
                </div>

                <!-- Price -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <p class="text-gray-600 text-sm mb-1">Harga</p>
                    <p class="text-3xl font-bold text-green-600">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Stock Info -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-gray-600 text-sm">Stok Tersedia</p>
                        <p class="text-lg font-bold text-gray-800">{{ $product->stock }} unit</p>
                    </div>
                    @if ($product->stock > 0)
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ min(($product->stock / 50) * 100, 100) }}%"></div>
                        </div>
                        <p class="text-xs text-green-600 mt-2">{{ $product->stock > 20 ? 'Stok terbatas' : 'Stok sangat terbatas' }}</p>
                    @else
                        <p class="text-red-600 font-semibold">Produk Habis</p>
                    @endif
                </div>

                <!-- Seller Info -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <p class="text-gray-600 text-sm mb-3">Dijual oleh</p>
                    <a href="{{ route('pembeli.sellers.show', $product->user->id) }}" class="flex items-center gap-3 hover:opacity-80 transition">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">{{ substr($product->user->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 hover:text-green-600">{{ $product->user->name }}</p>
                            <p class="text-xs text-gray-500">Lihat Toko</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <!-- Rating & Reviews -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <p class="text-gray-600 text-sm mb-3">Rating & Ulasan</p>
                    <div class="flex items-center gap-2 mb-2">
                        <div class="flex gap-1">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 {{ $i < 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm font-semibold text-gray-700">4.0 (12 ulasan)</span>
                    </div>
                    <p class="text-xs text-gray-500">Berdasarkan 12 ulasan dari pembeli</p>
                </div>

                <!-- Action Buttons -->
                @if ($product->stock > 0)
                    <div x-data="{ quantity: 1 }" class="space-y-3">
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <p class="text-gray-600 text-sm mb-2">Jumlah</p>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="quantity > 1 && quantity--" class="bg-gray-300 hover:bg-gray-400 px-3 py-2 rounded font-bold">
                                    −
                                </button>
                                <input 
                                    type="number" 
                                    x-model.number="quantity" 
                                    min="1" 
                                    max="{{ $product->stock }}"
                                    class="w-16 text-center border border-gray-300 rounded px-2 py-2 font-semibold"
                                >
                                <button type="button" @click="quantity < {{ $product->stock }} && quantity++" class="bg-gray-300 hover:bg-gray-400 px-3 py-2 rounded font-bold">
                                    +
                                </button>
                                <span class="ml-auto text-sm text-gray-600">Stok: {{ $product->stock }}</span>
                            </div>
                        </div>

                        <form method="GET" action="{{ route('pembeli.checkout.index') }}" class="w-full">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" x-model.number="quantity">
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition mb-3">
                                Beli Sekarang
                            </button>
                        </form>

                        <form action="{{ route('pembeli.cart.add') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" x-model.number="quantity">
                            <button type="submit" class="w-full border-2 border-green-500 text-green-500 hover:bg-green-50 font-bold py-3 rounded-lg transition">
                                🛒 Masukkan Keranjang
                            </button>
                        </form>
                    </div>
                @else
                    <button disabled class="w-full bg-gray-300 text-gray-600 font-bold py-3 rounded-lg cursor-not-allowed">
                        Stok Habis
                    </button>
                @endif
            </div>
            <!-- Seller Stats -->
            <div class="bg-white rounded-2xl shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Info Penjual</h3>
                <div class="space-y-4 mb-4 pb-4 border-b border-gray-200">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Rating Toko</span>
                        <span class="font-bold text-gray-800">4.5</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Produk Aktif</span>
                        <span class="font-bold text-gray-800">28</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Penjualan</span>
                        <span class="font-bold text-gray-800">156 terjual</span>
                    </div>
                </div>
                <a href="{{ route('pembeli.sellers.show', $product->user->id) }}" class="w-full border-2 border-blue-500 text-blue-500 hover:bg-blue-50 font-bold py-2 rounded-lg transition block text-center">
                    Lihat Toko Penjual
                </a>
            </div>
    </div>
</div>
    <!-- Reviews Section -->
    <div class="mt-12 bg-white rounded-2xl shadow p-8 w-full">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Ulasan Pembeli</h2>

        @php
            $reviews = [
                [
                    'name' => 'Budi Santoso',
                    'avatar' => 'BS',
                    'rating' => 5,
                    'date' => '2 hari lalu',
                    'comment' => 'Produk sesuai deskripsi, penjual ramah dan pengiriman cepat. Sangat recommended!',
                    'verified' => true
                ],
                [
                    'name' => 'Siti Nurhaliza',
                    'avatar' => 'SN',
                    'rating' => 4,
                    'date' => '1 minggu lalu',
                    'comment' => 'Bagus, hanya saja ada sedikit goresan di bagian sudut. Tapi masih ok kok.',
                    'verified' => true
                ],
                [
                    'name' => 'Ahmad Wijaya',
                    'avatar' => 'AW',
                    'rating' => 5,
                    'date' => '2 minggu lalu',
                    'comment' => 'Kualitas premium dengan harga yang terjangkau. Puas banget sama barangnya!',
                    'verified' => true
                ]
            ];
        @endphp

        <div class="space-y-6">
            @foreach ($reviews as $review)
                <div class="pb-6 border-b border-gray-200 last:border-0">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold">{{ $review['avatar'] }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <p class="font-semibold text-gray-800">{{ $review['name'] }}</p>
                                    @if ($review['verified'])
                                        <span class="inline-flex items-center gap-1 text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Pembeli Terverifikasi
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">{{ $review['date'] }}</p>
                            </div>
                            <div class="flex gap-1 mb-2">
                                @for ($i = 0; $i < 5; $i++)
                                    <svg class="w-4 h-4 {{ $i < $review['rating'] ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-gray-700">{{ $review['comment'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <button class="border-2 border-green-500 text-green-500 hover:bg-green-50 font-bold py-2 px-6 rounded-lg transition">
                Lihat Lebih Banyak Ulasan
            </button>
        </div>
    </div>

<script>
    function imageCarousel() {
        return {
            currentIndex: 0,
            totalImages: {{ $product->images ? count($product->images) : 1 }},
            next() {
                this.currentIndex = (this.currentIndex + 1) % this.totalImages;
            },
            prev() {
                this.currentIndex = (this.currentIndex - 1 + this.totalImages) % this.totalImages;
            }
        }
    }
</script>
@endsection

