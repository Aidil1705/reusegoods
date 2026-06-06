<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['province_id' => 1, 'province' => 'Banten'],
            ['province_id' => 2, 'province' => 'Bengkulu'],
            ['province_id' => 3, 'province' => 'DI Yogyakarta'],
            ['province_id' => 4, 'province' => 'DKI Jakarta'],
            ['province_id' => 5, 'province' => 'Gorontalo'],
            ['province_id' => 6, 'province' => 'Jambi'],
            ['province_id' => 7, 'province' => 'Jawa Barat'],
            ['province_id' => 8, 'province' => 'Jawa Tengah'],
            ['province_id' => 9, 'province' => 'Jawa Timur'],
            ['province_id' => 10, 'province' => 'Kalimantan Barat'],
            ['province_id' => 11, 'province' => 'Kalimantan Selatan'],
            ['province_id' => 12, 'province' => 'Kalimantan Tengah'],
            ['province_id' => 13, 'province' => 'Kalimantan Timur'],
            ['province_id' => 14, 'province' => 'Kalimantan Utara'],
            ['province_id' => 15, 'province' => 'Kepulauan Bangka Belitung'],
            ['province_id' => 16, 'province' => 'Kepulauan Riau'],
            ['province_id' => 17, 'province' => 'Lampung'],
            ['province_id' => 18, 'province' => 'Maluku'],
            ['province_id' => 19, 'province' => 'Maluku Utara'],
            ['province_id' => 20, 'province' => 'Nanggroe Aceh Darussalam'],
            ['province_id' => 21, 'province' => 'Nusa Tenggara Barat'],
            ['province_id' => 22, 'province' => 'Nusa Tenggara Timur'],
            ['province_id' => 23, 'province' => 'Papua'],
            ['province_id' => 24, 'province' => 'Papua Barat'],
            ['province_id' => 25, 'province' => 'Riau'],
            ['province_id' => 26, 'province' => 'Sulawesi Barat'],
            ['province_id' => 27, 'province' => 'Sulawesi Selatan'],
            ['province_id' => 28, 'province' => 'Sulawesi Tengah'],
            ['province_id' => 29, 'province' => 'Sulawesi Tenggara'],
            ['province_id' => 30, 'province' => 'Sulawesi Utara'],
            ['province_id' => 31, 'province' => 'Sumatera Barat'],
            ['province_id' => 32, 'province' => 'Sumatera Selatan'],
            ['province_id' => 33, 'province' => 'Sumatera Utara'],
        ];

        DB::table('provinces')->truncate();
        DB::table('provinces')->insert($provinces);
    }
}
