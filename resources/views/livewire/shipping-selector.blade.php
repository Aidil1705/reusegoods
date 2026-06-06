<div class="mb-6 pb-6 border-b border-gray-200">
    <h3 class="text-sm font-bold text-gray-700 mb-4">📍 Alamat Pengiriman</h3>
    
    <!-- Province Selection -->
    <div class="mb-3">
        <label class="block text-xs font-semibold text-gray-600 mb-1">Provinsi</label>
        <select wire:model.live="selectedProvince" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            <option value="">Pilih Provinsi</option>
            @foreach($provinces as $province)
                <option value="{{ $province->province_id }}">{{ $province->province }}</option>
            @endforeach
        </select>
    </div>

    <!-- City Selection -->
    <div class="mb-3">
        <label class="block text-xs font-semibold text-gray-600 mb-1">Kota/Kabupaten</label>
        <select wire:model.live="selectedCity" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" {{ empty($cities) ? 'disabled' : '' }}>
            <option value="">Pilih Kota</option>
            @foreach($cities as $city)
                <option value="{{ $city->city_id }}">{{ $city->city_name }}</option>
            @endforeach
        </select>
    </div>

    <!-- District Selection -->
    <div class="mb-3">
        <label class="block text-xs font-semibold text-gray-600 mb-1">Kecamatan</label>
        <select wire:model.live="selectedDistrict" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" {{ empty($districts) ? 'disabled' : '' }}>
            <option value="">Pilih Kecamatan</option>
            @foreach($districts as $district)
                <option value="{{ $district->district_id }}">{{ $district->district_name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Subdistrict Selection -->
    <div class="mb-3">
        <label class="block text-xs font-semibold text-gray-600 mb-1">Kelurahan</label>
        <select wire:model.live="selectedSubdistrict" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" {{ empty($subdistricts) ? 'disabled' : '' }}>
            <option value="">Pilih Kelurahan</option>
            @foreach($subdistricts as $subdistrict)
                <option value="{{ $subdistrict->subdistrict_id }}">{{ $subdistrict->subdistrict_name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Courier Selection -->
    <div class="mb-3">
        <label class="block text-xs font-semibold text-gray-600 mb-1">Kurir</label>
        <select wire:model="selectedCourier" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            <option value="jne">JNE</option>
            <option value="tiki">TIKI</option>
            <option value="pos">POS Indonesia</option>
        </select>
    </div>

    <!-- Calculate Shipping Button -->
    <button wire:click="calculateShipping" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded text-sm transition" {{ empty($selectedSubdistrict) ? 'disabled opacity-50' : '' }}>
        Hitung Ongkir
    </button>

    <!-- Error Message -->
    @if($errorMessage)
        <div class="mt-2 text-red-600 text-xs bg-red-50 p-2 rounded">
            {{ $errorMessage }}
        </div>
    @endif

    <!-- Success Message -->
    @if($successMessage)
        <div class="mt-2 text-green-600 text-xs bg-green-50 p-2 rounded">
            {{ $successMessage }}
        </div>
    @endif
</div>
