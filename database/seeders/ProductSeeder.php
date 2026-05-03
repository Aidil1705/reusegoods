<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'iPhone 11 Bekas',
                'category_id' => 1,
                'price' => 5000000,
                'condition' => 'bekas',
                'description' => 'iPhone 11 bekas, kondisi sangat baik, semua fungsi normal, baterai masih awet.',
            ],
            [
                'name' => 'Jaket Hoodie Uniqlo',
                'category_id' => 2,
                'price' => 150000,
                'condition' => 'bekas',
                'description' => 'Jaket hoodie Uniqlo original, bahan tebal dan nyaman dipakai sehari-hari.',
            ],
            [
                'name' => 'Blender Philips',
                'category_id' => 3,
                'price' => 300000,
                'condition' => 'bekas',
                'description' => 'Blender Philips masih berfungsi dengan baik, cocok untuk kebutuhan dapur.',
            ],
            [
                'name' => 'Laptop Asus VivoBook',
                'category_id' => 1,
                'price' => 4500000,
                'condition' => 'bekas',
                'description' => 'Laptop Asus VivoBook bekas, performa masih bagus untuk kerja dan kuliah.',
            ],
            [
                'name' => 'Sepatu Sneakers Nike',
                'category_id' => 2,
                'price' => 400000,
                'condition' => 'bekas',
                'description' => 'Sepatu sneakers Nike original, nyaman dan masih layak pakai.',
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->insert([
                'user_id' => 2, // pastikan user ini ada
                'category_id' => $product['category_id'],
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => 1,
                'condition' => $product['condition'],
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}