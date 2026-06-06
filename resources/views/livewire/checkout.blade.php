<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <div class="mb-6 text-sm text-gray-600">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-green-600">Beranda</a>
        <span class="mx-2">/</span>
        <a href="{{ route('pembeli.cart.index') }}" class="hover:text-green-600">Keranjang</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">Checkout</span>
    </div>

    <!-- Error Alert -->
    @if ($errorMessage)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <p class="font-bold">Error!</p>
            <p class="text-sm">{{ $errorMessage }}</p>
        </div>
    @endif

    <!-- Success Alert -->
    @if ($successMessage)
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <p class="font-bold">Berhasil!</p>
            <p class="text-sm">{{ $successMessage }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Items Column -->
        <div class="lg:col-span-2">
            <!-- Items Review -->
            <div class="bg-white rounded-2xl shadow p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Produk Pesanan</h2>

                <div class="space-y-4">
                    @forelse($items as $index => $item)
                        <div class="border-b border-gray-200 pb-4 last:border-0">
                            <div class="flex gap-4">
                                <!-- Product Image -->
                                <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                    @if ($item['image'])
                                        <img 
                                            src="{{ asset('storage/' . $item['image']) }}" 
                                            alt="{{ $item['name'] }}"
                                            class="w-full h-full object-cover"
                                        >
                                    @else
                                        <img 
                                            src="{{ asset('images/no-image.png') }}" 
                                            alt="No image"
                                            class="w-full h-full object-cover"
                                        >
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800">{{ $item['name'] }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Penjual: 
                                        <span class="text-green-600">{{ $item['seller'] }}</span>
                                    </p>
                                    <p class="text-green-600 font-bold mt-2">
                                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                                    </p>
                                </div>

                                <!-- Quantity & Subtotal -->
                                <div class="text-right">
                                    <p class="text-sm text-gray-600 mb-2">Qty: {{ $item['quantity'] }}</p>
                                    <p class="font-bold text-lg text-green-600">
                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </p>
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

                <!-- Province Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Provinsi</label>
                    <select 
                        wire:model.live="selectedProvince"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        <option value="">Pilih Provinsi</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->province_id }}">{{ $province->province }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- City Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kota/Kabupaten</label>
                    <select 
                        wire:model.live="selectedCity"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 disabled:bg-gray-100"
                        {{ !$selectedProvince ? 'disabled' : '' }}
                    >
                        <option value="">Pilih Kota</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->city_id }}">{{ $city->city_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- District Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kecamatan</label>
                    <select 
                        wire:model.live="selectedDistrict"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 disabled:bg-gray-100"
                        {{ !$selectedCity ? 'disabled' : '' }}
                    >
                        <option value="">Pilih Kecamatan</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->district_id }}">{{ $district->district_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Subdistrict Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kelurahan</label>
                    <select 
                        wire:model.live="selectedSubdistrict"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 disabled:bg-gray-100"
                        {{ !$selectedDistrict ? 'disabled' : '' }}
                    >
                        <option value="">Pilih Kelurahan</option>
                        @foreach($subdistricts as $subdistrict)
                            <option value="{{ $subdistrict->subdistrict_id }}">{{ $subdistrict->subdistrict_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Courier Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kurir</label>
                    <select 
                        wire:model="selectedCourier"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        <option value="jne">JNE</option>
                        <option value="tiki">TIKI</option>
                        <option value="pos">POS Indonesia</option>
                    </select>
                </div>

                <!-- Calculate Shipping Button -->
                <button 
                    wire:click="calculateShipping"
                    {{ !$selectedSubdistrict ? 'disabled' : '' }}
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded-lg transition mb-6 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Hitung Ongkir
                </button>

                <!-- Manual Address Entry -->
                <div class="pt-6 border-t border-gray-200">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap (Detail)</label>
                    <textarea 
                        wire:model="address"
                        rows="4" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Masukkan detail alamat lengkap pengiriman..."
                    ></textarea>
                </div>
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
                        <span class="font-semibold">{{ count($items) }} item</span>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal</span>
                        <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Ongkir</span>
                        <span class="font-semibold text-blue-600">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Total -->
                <div class="bg-green-50 p-4 rounded-lg mb-6 border border-green-200">
                    <div class="flex justify-between">
                        <span class="font-bold text-lg text-green-600">Total Pembayaran</span>
                        <span class="font-bold text-lg text-green-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <button 
                    wire:click="checkout"
                    wire:loading.attr="disabled"
                    {{ empty($items) || !$address || $shippingCost <= 0 ? 'disabled' : '' }}
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition mb-3 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    @if ($isProcessing)
                        <span wire:loading>Memproses...</span>
                    @else
                        <span>Lanjutkan ke Pembayaran</span>
                    @endif
                </button>

                <a href="{{ route('pembeli.cart.index') }}" class="w-full border-2 border-green-500 text-green-500 hover:bg-green-50 font-bold py-3 rounded-lg transition block text-center">
                    Kembali ke Keranjang
                </a>
            </div>
        </div>
    </div>
</div>
