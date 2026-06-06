@extends('layouts.pembeli')

@section('title', 'Checkout - ReGoods')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <div class="mb-6 text-sm text-gray-600">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-green-600">Beranda</a>
        <span class="mx-2">/</span>
        <a href="{{ route('pembeli.cart.index') }}" class="hover:text-green-600">Keranjang</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Checkout</span>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <p class="font-bold">Berhasil!</p>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('pembeli.checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf

        <!-- Items Column -->
        <div class="lg:col-span-2">
            <!-- Items Review -->
            <div class="bg-white rounded-2xl shadow p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Produk Pesanan</h2>

                <div class="space-y-4">
                    @forelse($items as $item)
                        <div class="border-b border-gray-200 pb-4 last:border-0">
                            <div class="flex gap-4">
                                <!-- Product Image -->
                                <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                    @php
                                        $image = $item->product->primaryImage ?? $item->product->images()->first();
                                    @endphp
                                    <img 
                                        src="{{ $image ? asset('storage/' . $image->image_url) : asset('images/no-image.png') }}" 
                                        alt="{{ $item->product->name }}"
                                        class="w-full h-full object-cover"
                                    >
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Penjual: 
                                        <span class="text-green-600">{{ $item->product->user->name }}</span>
                                    </p>
                                    <p class="text-green-600 font-bold mt-2">
                                        Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <!-- Quantity & Subtotal -->
                                <div class="text-right">
                                    <p class="text-sm text-gray-600 mb-2">Qty: {{ $item->quantity }}</p>
                                    <p class="font-bold text-lg text-green-600">
                                        Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                    </p>

                                    <!-- Hidden input for selected items -->
                                    @if (!$isBuyNow)
                                        <input type="hidden" name="items[]" value="{{ $item->id }}">
                                    @else
                                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                        <input type="hidden" name="quantity" value="{{ $item->quantity }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600 text-center py-6">Tidak ada produk</p>
                    @endforelse
                </div>
            </div>

            <!-- Shipping Address & Cost Calculation -->
            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">📍 Pilih Alamat & Hitung Ongkir</h2>

                <!-- Livewire Shipping Selector -->
                @livewire('shipping-selector')

                <!-- Manual Address Entry -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap (Detail)</label>
                    <textarea 
                        name="address" 
                        rows="4" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Masukkan detail alamat lengkap pengiriman (nama jalan, nomor rumah, RT/RW, dll)..."
                        required
                    ></textarea>
                    @error('address')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hidden inputs to capture shipping data from Livewire -->
                <input type="hidden" id="destination_subdistrict_id" name="destination_subdistrict_id">
                <input type="hidden" id="shipping_cost" name="shipping_cost" value="0">
                <input type="hidden" id="courier" name="courier" value="jne">
                <input type="hidden" id="courier_service" name="courier_service">
            </div>
        </div>

        <!-- Summary Column -->
        <div>
            <div class="bg-white rounded-2xl shadow p-6 sticky top-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Ringkasan Pesanan</h2>

                <!-- Order Summary -->
                <div class="space-y-3 pb-4 border-b border-gray-200 mb-4">
                    <div class="flex justify-between text-gray-700">
                        <span>Total Produk</span>
                        <span class="font-semibold">{{ $items->count() }} item</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Jumlah Unit</span>
                        <span class="font-semibold">{{ $items->sum('quantity') }} unit</span>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal</span>
                        <span class="font-semibold" id="displaySubtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Ongkir</span>
                        <span class="font-semibold text-blue-600" id="displayShipping">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Diskon</span>
                        <span class="font-semibold text-red-600">-Rp 0</span>
                    </div>
                </div>

                <!-- Total -->
                <div class="bg-green-50 p-4 rounded-lg mb-6 border border-green-200">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-700">Total</span>
                        <span class="text-gray-700" id="displayTotalAmount">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-bold text-lg text-green-600">Total Pembayaran</span>
                        <span class="font-bold text-lg text-green-600" id="displayTotalPayment">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition mb-3">
                    Lanjutkan ke Pembayaran
                </button>

                <a href="{{ route('pembeli.cart.index') }}" class="w-full border-2 border-green-500 text-green-500 hover:bg-green-50 font-bold py-3 rounded-lg transition block text-center">
                    Kembali ke Keranjang
                </a>
            </div>
        </div>
    </form>
</div>

<script>
    // Subtotal dari produk
    const subtotal = {{ $subtotal }};

    // Listen untuk update shipping cost dari Livewire
    document.addEventListener('livewire:updated', function(event) {
        // Jika ada perubahan di Livewire shipping selector
        if (event.detail.component && event.detail.component.includes('shipping')) {
            updatePriceDisplay();
        }
    });

    // Listen untuk event Livewire shipping-cost-updated
    window.addEventListener('shipping-cost-updated', function(event) {
        const detail = event.detail;
        
        // Update hidden inputs dengan data dari Livewire
        if (detail.shippingCost !== undefined) {
            document.getElementById('shipping_cost').value = detail.shippingCost;
        }
        if (detail.destinationSubdistrictId) {
            document.getElementById('destination_subdistrict_id').value = detail.destinationSubdistrictId;
        }
        if (detail.courier) {
            document.getElementById('courier').value = detail.courier;
        }
        if (detail.courierService) {
            document.getElementById('courier_service').value = detail.courierService;
        }
        
        updatePriceDisplay();
    });

    // Direct Livewire listener untuk event dispatch
    Livewire.on('shipping-cost-updated', (data) => {
        document.getElementById('shipping_cost').value = data.shippingCost || 0;
        if (data.destinationSubdistrictId) {
            document.getElementById('destination_subdistrict_id').value = data.destinationSubdistrictId;
        }
        if (data.courier) {
            document.getElementById('courier').value = data.courier;
        }
        if (data.courierService) {
            document.getElementById('courier_service').value = data.courierService;
        }
        updatePriceDisplay();
    });

    function updatePriceDisplay() {
        const shippingCost = parseInt(document.getElementById('shipping_cost').value) || 0;
        const total = subtotal + shippingCost;

        // Format angka ke Rupiah
        const formatRupiah = (num) => 'Rp ' + num.toLocaleString('id-ID');

        // Update tampilan
        document.getElementById('displaySubtotal').textContent = formatRupiah(subtotal);
        document.getElementById('displayShipping').textContent = formatRupiah(shippingCost);
        document.getElementById('displayTotalAmount').textContent = formatRupiah(total);
        document.getElementById('displayTotalPayment').textContent = formatRupiah(total);
    }

    // Initialize tampilan
    updatePriceDisplay();
</script>

@endsection
