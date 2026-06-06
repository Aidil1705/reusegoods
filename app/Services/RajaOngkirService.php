<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RajaOngkirService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.key');
        $this->baseUrl = rtrim(config('services.rajaongkir.base_url', 'https://rajaongkir.komerce.id/api/v1'), '/');
    }

    /**
     * Get list of provinces
     */
    public function getProvinces()
    {
        return Cache::remember('rajaongkir_provinces', 24 * 60, function () {
            if ($this->hasApiKey()) {
                try {
                    $response = $this->request()->get($this->baseUrl . '/destination/province');

                    if ($response->successful()) {
                        return collect($response->json('data', []))
                            ->map(fn ($province) => (object) [
                                'province_id' => (string) ($province['id'] ?? ''),
                                'province' => $province['name'] ?? '',
                            ])
                            ->filter(fn ($province) => $province->province_id !== '')
                            ->values()
                            ->all();
                    }
                } catch (\Exception $e) {
                    \Log::warning('RajaOngkir API Error: ' . $e->getMessage());
                }
            }

            // Fallback to database
            return DB::table('provinces')->get()->toArray();
        });
    }

    /**
     * Get cities by province ID
     */
    public function getCities($provinceId)
    {
        $cacheKey = "rajaongkir_cities_{$provinceId}";
        
        return Cache::remember($cacheKey, 24 * 60, function () use ($provinceId) {
            if ($this->hasApiKey()) {
                try {
                    $response = $this->request()->get($this->baseUrl . '/destination/city/' . $provinceId);

                    if ($response->successful()) {
                        return collect($response->json('data', []))
                            ->map(fn ($city) => (object) [
                                'city_id' => (string) ($city['id'] ?? ''),
                                'city_name' => $city['name'] ?? '',
                                'city_type' => $city['type'] ?? null,
                            ])
                            ->filter(fn ($city) => $city->city_id !== '')
                            ->values()
                            ->all();
                    }
                } catch (\Exception $e) {
                    \Log::warning('RajaOngkir API Error: ' . $e->getMessage());
                }
            }

            // Fallback to database
            return DB::table('cities')
                ->where('province_id', $provinceId)
                ->get()
                ->toArray();
        });
    }

    /**
     * Get districts by city ID
     */
    public function getDistricts($cityId)
    {
        $cacheKey = "rajaongkir_districts_{$cityId}";
        
        return Cache::remember($cacheKey, 24 * 60, function () use ($cityId) {
            if ($this->hasApiKey()) {
                try {
                    $response = $this->request()->get($this->baseUrl . '/destination/district/' . $cityId);

                    if ($response->successful()) {
                        return collect($response->json('data', []))
                            ->map(fn ($district) => (object) [
                                'district_id' => (string) ($district['id'] ?? ''),
                                'district_name' => $district['name'] ?? '',
                            ])
                            ->filter(fn ($district) => $district->district_id !== '')
                            ->values()
                            ->all();
                    }
                } catch (\Exception $e) {
                    \Log::warning('RajaOngkir API Error: ' . $e->getMessage());
                }
            }

            // Fallback to database
            return DB::table('districts')
                ->where('city_id', $cityId)
                ->get()
                ->toArray();
        });
    }

    /**
     * Get subdistricts by district ID
     */
    public function getSubdistricts($districtId)
    {
        $cacheKey = "rajaongkir_subdistricts_{$districtId}";
        
        return Cache::remember($cacheKey, 24 * 60, function () use ($districtId) {
            if ($this->hasApiKey()) {
                try {
                    $response = $this->request()->get($this->baseUrl . '/destination/sub-district/' . $districtId);

                    if ($response->successful()) {
                        return collect($response->json('data', []))
                            ->map(fn ($subdistrict) => (object) [
                                'subdistrict_id' => (string) ($subdistrict['id'] ?? ''),
                                'subdistrict_name' => $subdistrict['name'] ?? '',
                            ])
                            ->filter(fn ($subdistrict) => $subdistrict->subdistrict_id !== '')
                            ->values()
                            ->all();
                    }
                } catch (\Exception $e) {
                    \Log::warning('RajaOngkir API Error: ' . $e->getMessage());
                }
            }

            // Fallback to database
            return DB::table('subdistricts')
                ->where('district_id', $districtId)
                ->get()
                ->toArray();
        });
    }

    /**
     * Calculate shipping cost
     * 
     * @param int $originId - Origin ID from Search Domestic Destination
     * @param int $destinationSubdistrictId - Subdistrict ID of destination (buyer)
     * @param int $weight - Weight in grams
     * @param string $courier - Courier code (jne, tiki, pos)
     */
    public function calculateCost($originId, $destinationSubdistrictId, $weight, $courier = 'jne')
    {
        if (!$this->hasApiKey()) {
            return [
                'service' => strtoupper($courier),
                'description' => 'Pengiriman ' . strtoupper($courier),
                'cost' => 50000,
                'etd' => '1-2',
            ];
        }

        try {
            $response = $this->request()->asForm()->post($this->baseUrl . '/calculate/domestic-cost', [
                'origin' => $originId,
                'destination' => $destinationSubdistrictId,
                'weight' => $weight,
                'courier' => $courier,
                'price' => 'lowest',
            ]);

            if ($response->successful()) {
                $shippingOptions = $response->json('data', []);

                if (!empty($shippingOptions)) {
                    return $this->normalizeCost($shippingOptions[0]);
                }
            }

            \Log::warning('RajaOngkir API Error: ' . $response->body());
        } catch (\Exception $e) {
            \Log::warning('RajaOngkir API Error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Search destination by city name (Direct Search Method)
     */
    public function searchDestination($query, $limit = 10)
    {
        if (!$this->hasApiKey()) {
            return [];
        }

        try {
            $response = $this->request()->get($this->baseUrl . '/destination/domestic-destination', [
                'search' => $query,
                'limit' => $limit,
                'offset' => 0
            ]);

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }
        } catch (\Exception $e) {
            \Log::warning('RajaOngkir API Error: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Get tracking info by waybill number
     */
    public function trackWaybill($waybill, $courier)
    {
        if (!$this->hasApiKey()) {
            return null;
        }

        try {
            $response = $this->request()->asForm()->post($this->baseUrl . '/track/waybill', [
                'waybill' => $waybill,
                'courier' => $courier
            ]);

            if ($response->successful()) {
                return $response->json()['data'] ?? null;
            }
        } catch (\Exception $e) {
            \Log::warning('RajaOngkir API Error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get minimum weight requirement for couriers
     */
    public function getCourierInfo()
    {
        return [
            'jne' => ['name' => 'JNE', 'min_weight' => 1000],
            'tiki' => ['name' => 'TIKI', 'min_weight' => 100],
            'pos' => ['name' => 'POS Indonesia', 'min_weight' => 1000],
        ];
    }

    private function hasApiKey(): bool
    {
        return filled($this->apiKey) && $this->apiKey !== 'your_api_key_here';
    }

    private function request()
    {
        return Http::withHeaders([
            'key' => $this->apiKey,
            'Key' => $this->apiKey,
        ])->timeout(15);
    }

    private function normalizeCost(array $option): array
    {
        return [
            'name' => $option['name'] ?? '',
            'code' => $option['code'] ?? '',
            'service' => $option['service'] ?? ($option['code'] ?? ''),
            'description' => $option['description'] ?? '',
            'cost' => (int) ($option['cost'] ?? 0),
            'etd' => $option['etd'] ?? '',
        ];
    }
}
