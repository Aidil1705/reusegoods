<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'condition',
        'status'
    ];

    // relasi ke images
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // ambil gambar utama
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', 1);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
