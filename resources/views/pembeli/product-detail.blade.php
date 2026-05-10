@extends('layouts.pembeli')

@section('title', $product->name . ' - ReGoods')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('pembeli.dashboard') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold mb-6 inline-flex items-center gap-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
        Kembali
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Image Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6">
                <!-- Main Image -->
                @php
                    $mainImage = $product->images()->first();
                @endphp
                <div class="mb-6">
                    <img 
                        src="{{ $mainImage ? asset('storage/' . $mainImage->image_url) : asset('images/no-image.png') }}" 
                        alt="{{ $product->name }}"
                        class="w-full h-80 object-cover rounded-xl"
                        id="mainImage"
                    >
                </div>

                <!-- Thumbnail Images -->
                @if($product->images()->count() > 1)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->images as $image)
                            <img 
                                src="{{ asset('storage/' . $image->image_url) }}" 
                                alt="{{ $product->name }}"
                                class="w-full h-20 object-cover rounded-lg cursor-pointer hover:ring-2 hover:ring-emerald-500 transition"
                                onclick="document.getElementById('mainImage').src = this.src"
                            >
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Info Section -->
        <div class="space-y-6">
            <!-- Product Title and Price -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
                
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-xs bg-{{ $product->condition === 'baru' ? 'blue' : 'amber' }}-100 text-{{ $product->condition === 'baru' ? 'blue' : 'amber' }}-800 px-3 py-1 rounded-full font-semibold">
                        {{ ucfirst($product->condition) }}
                    </span>
                    <span class="text-xs bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold">
                        Stok: {{ $product->stock }}
                    </span>
                </div>

                <div class="text-4xl font-bold text-green-600 mb-2">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>

                <p class="text-sm text-gray-600 mb-4">
                    Kategori: <span class="font-semibold text-gray-800">{{ $product->category->name }}</span>
                </p>
            </div>

            <!-- Seller Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-bold text-gray-800 mb-4">Informasi Penjual</h3>
                
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="font-bold text-gray-700">{{ strtoupper(substr($product->user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $product->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $product->user->email }}</p>
                    </div>
                </div>

                    @if(Auth::check() && Auth::id() !== $product->user_id)
                        <form id="chatForm{{ $product->user->id }}" action="{{ route('chat.start') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $product->user->id }}">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                                Chat dengan Penjual
                            </button>
                        </form>
                    @else
                        <div class="w-full bg-gray-100 text-gray-500 font-semibold py-3 rounded-xl flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                            Ini produk Anda
                        </div>
                    @endif
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-3">
                <button type="button" onclick="toggleWishlist({{ $product->id }})" id="wishlistBtn" class="bg-white hover:bg-red-50 border border-gray-300 text-gray-800 font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 wishlist-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    <span id="wishlistText">Wishlist</span>
                </button>
                <button type="button" onclick="addToCart({{ $product->id }})" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 4V3c0-.55.45-1 1-1s1 .45 1 1v1h6V3c0-.55.45-1 1-1s1 .45 1 1v1h3c1.1 0 2 .9 2 2v16c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2h3zm9 18H8v-2h8v2zm3-4H4V8h16v10z"/></svg>
                    Beli Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- Description Section -->
    <div class="mt-12 bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Deskripsi Produk</h2>
        <p class="text-gray-700 leading-relaxed">
            {{ $product->description ?: 'Tidak ada deskripsi produk' }}
        </p>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Produk Lainnya dari Penjual Ini</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
                        @php
                            $image = $related->images()->first();
                        @endphp

                        <img 
                            src="{{ $image ? asset('storage/' . $image->image_url) : asset('images/no-image.png') }}" 
                            class="w-full h-40 object-cover"
                            alt="{{ $related->name }}"
                        >

                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 line-clamp-2">
                                {{ $related->name }}
                            </h3>

                            <p class="text-green-600 font-bold mt-1">
                                Rp {{ number_format($related->price, 0, ',', '.') }}
                            </p>

                            <span class="text-xs bg-gray-200 px-2 py-1 rounded-full inline-block mt-2">
                                {{ ucfirst($related->condition) }}
                            </span>

                            <a href="{{ route('pembeli.product.show', $related) }}" class="mt-3 w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg inline-block text-center text-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
    const wishlistBaseUrl = @json(url('/wishlist'));
    const cartBaseUrl = @json(url('/cart'));

    // Check wishlist status on page load
    document.addEventListener('DOMContentLoaded', function() {
        checkWishlistStatus({{ $product->id }});
    });

    // Toggle wishlist
    function toggleWishlist(productId) {
        if (!document.querySelector('meta[name="csrf-token"]')) {
            alert('Session belum siap, silakan muat ulang halaman.');
            return;
        }

        fetch(`${wishlistBaseUrl}/${productId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'added') {
                updateWishlistUI(true, data.message);
            } else {
                updateWishlistUI(false, data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }

    // Check if product is in wishlist
    function checkWishlistStatus(productId) {
        fetch(`${wishlistBaseUrl}/${productId}/check`)
            .then(response => response.json())
            .then(data => {
                if (data.inWishlist) {
                    updateWishlistUI(true);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Update wishlist UI
    function updateWishlistUI(inWishlist, message = '') {
        const btn = document.getElementById('wishlistBtn');
        const icon = btn.querySelector('.wishlist-icon');
        const text = document.getElementById('wishlistText');

        if (inWishlist) {
            icon.setAttribute('fill', 'currentColor');
            icon.setAttribute('stroke', 'none');
            icon.classList.add('text-red-500');
            btn.classList.add('text-red-500');
            text.textContent = 'Di Wishlist';
        } else {
            icon.setAttribute('fill', 'none');
            icon.setAttribute('stroke', 'currentColor');
            icon.classList.remove('text-red-500');
            btn.classList.remove('text-red-500');
            text.textContent = 'Wishlist';
        }

        if (message) {
            showNotification(message);
        }
    }

    // Add to cart
    function addToCart(productId) {
        if (!document.querySelector('meta[name="csrf-token"]')) {
            alert('Session belum siap, silakan muat ulang halaman.');
            return;
        }

        fetch(`${cartBaseUrl}/${productId}/add`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'added') {
                showNotification('✓ ' + data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }

    // Show notification
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed bottom-5 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg animate-pulse';
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>
@endsection
