<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
            $productImages = [
        1 => ['iphone1.jpg', 'iphone2.jpg', 'iphone3.jpg'],
        2 => ['uniqlo1.jpg'], // cuma 1 gambar
        3 => ['blender1.jpg'],
        4 => ['asus1.jpg'],
        5 => ['nike1.jpg'],
    ];

    foreach ($productImages as $productId => $images) {
        foreach ($images as $index => $img) {
            DB::table('product_images')->insert([
                'product_id' => $productId,
                'image_url' => 'images/products/' . $img,
                'is_primary' => $index === 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    }
}


