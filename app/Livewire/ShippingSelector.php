<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\RajaOngkirService;
use Illuminate\Support\Facades\DB;

class ShippingSelector extends Component
{
    public $provinces = [];
    public $cities = [];
    public $districts = [];
    public $subdistricts = [];

    public $selectedProvince = '';
    public $selectedCity = '';
    public $selectedDistrict = '';
    public $selectedSubdistrict = '';
    public $selectedCourier = 'jne';

    public $shippingCost = 0;
    public $shippingService = '';
    public $shippingEtd = '';
    public $errorMessage = '';
    public $successMessage = '';

    protected $rajaOngkir;

    public function mount()
    {
        $this->rajaOngkir = app(RajaOngkirService::class);
        $this->loadProvinces();
    }

    public function loadProvinces()
    {
        $this->provinces = DB::table('provinces')->get()->toArray();
    }

    public function updatedSelectedProvince($value)
    {
        if (!$value) {
            $this->cities = [];
            $this->districts = [];
            $this->subdistricts = [];
            $this->selectedCity = '';
            $this->selectedDistrict = '';
            $this->selectedSubdistrict = '';
            return;
        }

        $this->cities = DB::table('cities')
            ->where('province_id', $value)
            ->get()
            ->toArray();
        
        $this->districts = [];
        $this->subdistricts = [];
        $this->selectedCity = '';
        $this->selectedDistrict = '';
        $this->selectedSubdistrict = '';
        $this->errorMessage = '';
    }

    public function updatedSelectedCity($value)
    {
        if (!$value) {
            $this->districts = [];
            $this->subdistricts = [];
            $this->selectedDistrict = '';
            $this->selectedSubdistrict = '';
            return;
        }

        $this->districts = DB::table('districts')
            ->where('city_id', $value)
            ->get()
            ->toArray();
        
        $this->subdistricts = [];
        $this->selectedDistrict = '';
        $this->selectedSubdistrict = '';
        $this->errorMessage = '';
    }

    public function updatedSelectedDistrict($value)
    {
        if (!$value) {
            $this->subdistricts = [];
            $this->selectedSubdistrict = '';
            return;
        }

        $this->subdistricts = DB::table('subdistricts')
            ->where('district_id', $value)
            ->get()
            ->toArray();
        
        $this->selectedSubdistrict = '';
        $this->errorMessage = '';
    }

    public function calculateShipping()
    {
        $this->errorMessage = '';
        $this->successMessage = '';

        if (!$this->selectedSubdistrict) {
            $this->errorMessage = 'Silakan pilih kelurahan terlebih dahulu';
            return;
        }

        try {
            $cart = auth()->user()->cart;
            if (!$cart || $cart->items()->count() === 0) {
                $this->errorMessage = 'Keranjang kosong';
                return;
            }

            // Calculate total weight
            $weight = $cart->items()->with('product')->get()->sum(function ($item) {
                return ($item->product->weight ?? 1000) * $item->quantity;
            });

            // Use first seller's district as origin (default to Jakarta)
            $originDistrictId = 1;
            
            // Calculate shipping
            $shippingInfo = $this->rajaOngkir->calculateCost(
                $originDistrictId,
                $this->selectedSubdistrict,
                $weight,
                $this->selectedCourier
            );

            if (!$shippingInfo) {
                $this->errorMessage = 'Gagal menghitung ongkir';
                return;
            }

            // Update cart with shipping info
            $cart->update([
                'destination_subdistrict_id' => $this->selectedSubdistrict,
                'shipping_cost' => $shippingInfo['cost'][0]['value'] ?? 0,
                'courier' => $this->selectedCourier,
                'courier_service' => $shippingInfo['service'] ?? null,
            ]);

            $this->shippingCost = $shippingInfo['cost'][0]['value'] ?? 0;
            $this->shippingService = $shippingInfo['service'] ?? '';
            $this->shippingEtd = $shippingInfo['cost'][0]['etd'] ?? '';
            $this->successMessage = "Ongkir berhasil dihitung: {$this->shippingService} ({$this->shippingEtd} hari)";

            // Dispatch events
            $this->dispatch('cart-updated', [
                'shippingCost' => $this->shippingCost,
            ]);
            
            // Emit JavaScript event for checkout page
            $this->dispatch('shipping-cost-updated', [
                'shippingCost' => $this->shippingCost,
                'destinationSubdistrictId' => $this->selectedSubdistrict,
                'courier' => $this->selectedCourier,
                'courierService' => $this->shippingService,
            ]);

        } catch (\Exception $e) {
            $this->errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.shipping-selector');
    }
}
