<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\RajaOngkirService;

class Checkout extends Component
{
    public $items = [];
    public $subtotal = 0;
    public $shippingCost = 0;
    public $total = 0;
    public $isBuyNow = false;
    
    // Shipping data
    public $provinces = [];
    public $cities = [];
    public $districts = [];
    public $subdistricts = [];
    
    public $selectedProvince = '';
    public $selectedCity = '';
    public $selectedDistrict = '';
    public $selectedSubdistrict = '';
    public $selectedCourier = 'jne';
    
    // Form data
    public $address = '';
    public $shippingService = '';
    public $shippingEtd = '';
    
    // Messages
    public $errorMessage = '';
    public $successMessage = '';
    public $isProcessing = false;

    #[\Livewire\Attributes\Computed]
    public function checkoutItems()
    {
        return $this->items;
    }

    public function mount($items = [], $subtotal = 0, $shippingCost = 0, $isBuyNow = false)
    {
        $this->items = $items;
        $this->subtotal = $subtotal;
        $this->shippingCost = $shippingCost;
        $this->isBuyNow = $isBuyNow;
        
        $this->loadProvinces();
        $this->calculateTotal();
    }

    public function loadProvinces()
    {
        $this->provinces = $this->rajaOngkir()->getProvinces();
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
            $this->shippingCost = 0;
            $this->calculateTotal();
            return;
        }

        $this->cities = $this->rajaOngkir()->getCities($value);
        
        $this->districts = [];
        $this->subdistricts = [];
        $this->selectedCity = '';
        $this->selectedDistrict = '';
        $this->selectedSubdistrict = '';
        $this->shippingCost = 0;
        $this->errorMessage = '';
        $this->calculateTotal();
    }

    public function updatedSelectedCity($value)
    {
        if (!$value) {
            $this->districts = [];
            $this->subdistricts = [];
            $this->selectedDistrict = '';
            $this->selectedSubdistrict = '';
            $this->shippingCost = 0;
            $this->calculateTotal();
            return;
        }

        $this->districts = $this->rajaOngkir()->getDistricts($value);
        
        $this->subdistricts = [];
        $this->selectedDistrict = '';
        $this->selectedSubdistrict = '';
        $this->shippingCost = 0;
        $this->errorMessage = '';
        $this->calculateTotal();
    }

    public function updatedSelectedDistrict($value)
    {
        if (!$value) {
            $this->subdistricts = [];
            $this->selectedSubdistrict = '';
            $this->shippingCost = 0;
            $this->calculateTotal();
            return;
        }

        $this->subdistricts = $this->rajaOngkir()->getSubdistricts($value);
        
        $this->selectedSubdistrict = '';
        $this->shippingCost = 0;
        $this->errorMessage = '';
        $this->calculateTotal();
    }

    public function updatedSelectedSubdistrict($value)
    {
        if ($value) {
            $this->shippingCost = 0;
            $this->shippingService = '';
            $this->shippingEtd = '';
            $this->calculateTotal();
        }
    }

    public function updatedSelectedCourier()
    {
        $this->shippingCost = 0;
        $this->shippingService = '';
        $this->shippingEtd = '';
        $this->successMessage = '';
        $this->calculateTotal();
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
            $weight = $this->calculateWeight();
            $originId = (int) config('services.rajaongkir.origin_id', 1);

            $shippingInfo = $this->rajaOngkir()->calculateCost(
                $originId,
                $this->selectedSubdistrict,
                $weight,
                $this->selectedCourier
            );

            if (!$shippingInfo) {
                $this->errorMessage = 'Gagal menghitung ongkir';
                return;
            }

            $this->shippingCost = (int) ($shippingInfo['cost'] ?? 0);
            $this->shippingService = $shippingInfo['service'] ?? '';
            $this->shippingEtd = $shippingInfo['etd'] ?? '';
            $this->successMessage = "Ongkir berhasil dihitung: {$this->shippingService} ({$this->shippingEtd} hari) - Rp " . number_format($this->shippingCost, 0, ',', '.');
            
            $this->calculateTotal();

        } catch (\Exception $e) {
            $this->errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }

    public function calculateTotal()
    {
        $this->subtotal = (int) $this->subtotal;
        $this->shippingCost = (int) $this->shippingCost;
        $this->total = $this->subtotal + $this->shippingCost;
    }

    public function checkout()
    {
        $this->errorMessage = '';

        if (!$this->address) {
            $this->errorMessage = 'Silakan masukkan alamat lengkap';
            return;
        }

        if (empty($this->items)) {
            $this->errorMessage = 'Tidak ada produk untuk checkout';
            return;
        }

        if (!$this->selectedSubdistrict) {
            $this->errorMessage = 'Silakan pilih kelurahan tujuan pengiriman';
            return;
        }

        if ($this->shippingCost <= 0) {
            $this->errorMessage = 'Silakan hitung ongkir terlebih dahulu';
            return;
        }

        $this->calculateTotal();
        $this->isProcessing = true;

        try {
            $user = auth()->user();

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()) . '-' . date('YmdHis'),
                'subtotal' => $this->subtotal,
                'shipping_cost' => $this->shippingCost,
                'discount' => 0,
                'total' => $this->total,
                'status' => 'pending',
                'courier' => $this->selectedCourier,
                'courier_service' => $this->shippingService,
                'destination_address' => $this->address,
                'destination_province' => $this->findLocationName($this->provinces, 'province_id', $this->selectedProvince, 'province'),
                'destination_city' => $this->findLocationName($this->cities, 'city_id', $this->selectedCity, 'city_name'),
                'destination_district' => $this->findLocationName($this->districts, 'district_id', $this->selectedDistrict, 'district_name'),
                'destination_subdistrict' => $this->findLocationName($this->subdistricts, 'subdistrict_id', $this->selectedSubdistrict, 'subdistrict_name'),
            ]);

            // Create order items
            foreach ($this->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            // Remove from cart if not buy now
            if (!$this->isBuyNow) {
                $cartItemIds = array_filter(array_column($this->items, 'id'));
                CartItem::whereIn('id', $cartItemIds)->delete();
            }

            $this->isProcessing = false;
            return redirect()->route('pembeli.checkout.confirmation', $order->id)
                ->with('success', 'Order berhasil dibuat. Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            $this->isProcessing = false;
            $this->errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }

    private function rajaOngkir(): RajaOngkirService
    {
        return app(RajaOngkirService::class);
    }

    private function calculateWeight(): int
    {
        $quantity = collect($this->items)->sum(fn ($item) => (int) ($item['quantity'] ?? 1));

        return max($quantity, 1) * 1000;
    }

    private function findLocationName(array $locations, string $idField, $id, string $nameField): ?string
    {
        foreach ($locations as $location) {
            if ((string) ($location->{$idField} ?? '') === (string) $id) {
                return $location->{$nameField} ?? null;
            }
        }

        return null;
    }
}
