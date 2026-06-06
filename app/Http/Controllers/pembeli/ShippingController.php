<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Services\RajaOngkirService;
use App\Models\Cart;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
        $this->middleware('auth');
    }

    /**
     * Get provinces
     */
    public function getProvinces()
    {
        try {
            $provinces = $this->rajaOngkir->getProvinces();
            return response()->json([
                'success' => true,
                'data' => $provinces
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data provinsi'
            ], 500);
        }
    }

    /**
     * Get cities by province
     */
    public function getCities($provinceId)
    {
        try {
            $cities = $this->rajaOngkir->getCities($provinceId);
            return response()->json([
                'success' => true,
                'data' => $cities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kota'
            ], 500);
        }
    }

    /**
     * Get districts by city
     */
    public function getDistricts($cityId)
    {
        try {
            $districts = $this->rajaOngkir->getDistricts($cityId);
            return response()->json([
                'success' => true,
                'data' => $districts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kecamatan'
            ], 500);
        }
    }

    /**
     * Get subdistricts by district
     */
    public function getSubdistricts($districtId)
    {
        try {
            $subdistricts = $this->rajaOngkir->getSubdistricts($districtId);
            return response()->json([
                'success' => true,
                'data' => $subdistricts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kelurahan'
            ], 500);
        }
    }

    /**
     * Calculate shipping cost
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'subdistrict_id' => 'required|integer',
            'courier' => 'required|string|in:jne,tiki,pos',
        ]);

        try {
            $user = auth()->user();
            $cart = $user->cart;

            if (!$cart || $cart->items()->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong'
                ], 400);
            }

            // Calculate total weight from cart items
            $weight = $cart->items()->with('product')->get()->sum(function ($item) {
                return ($item->product->weight ?? 1000) * $item->quantity;
            });

            // Use first seller's district as origin
            $originId = (int) config('services.rajaongkir.origin_id', 1);
            
            $shippingInfo = $this->rajaOngkir->calculateCost(
                $originId,
                $request->subdistrict_id,
                $weight,
                $request->courier
            );

            if (!$shippingInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghitung ongkir'
                ], 400);
            }

            // Update cart with shipping information
            $cart->update([
                'destination_subdistrict_id' => $request->subdistrict_id,
                'shipping_cost' => (int) ($shippingInfo['cost'] ?? 0),
                'courier' => $request->courier,
                'courier_service' => $shippingInfo['service'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ongkir berhasil dihitung',
                'data' => [
                    'service' => $shippingInfo['service'] ?? '',
                    'description' => $shippingInfo['description'] ?? '',
                    'cost' => (int) ($shippingInfo['cost'] ?? 0),
                    'etd' => $shippingInfo['etd'] ?? '',
                    'total_with_shipping' => $cart->getSubtotal() + (int) ($shippingInfo['cost'] ?? 0)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search destination by city name
     */
    public function searchDestination(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:2'
        ]);

        try {
            $results = $this->rajaOngkir->searchDestination($request->search);
            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencari destinasi'
            ], 500);
        }
    }

    /**
     * Update shipping destination
     */
    public function updateDestination(Request $request)
    {
        $request->validate([
            'destination_province' => 'required|string',
            'destination_city' => 'required|string',
            'destination_district' => 'required|string',
            'destination_subdistrict' => 'required|string',
        ]);

        try {
            $user = auth()->user();
            $cart = $user->cart;

            if (!$cart) {
                $cart = Cart::create(['user_id' => $user->id]);
            }

            $cart->update([
                'destination_province' => $request->destination_province,
                'destination_city' => $request->destination_city,
                'destination_district' => $request->destination_district,
                'destination_subdistrict' => $request->destination_subdistrict,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Alamat pengiriman berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui alamat'
            ], 500);
        }
    }
}
