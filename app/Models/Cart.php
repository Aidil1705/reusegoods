<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'destination_province',
        'destination_city',
        'destination_district',
        'destination_subdistrict',
        'destination_subdistrict_id',
        'shipping_cost',
        'courier',
        'courier_service',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get cart total price including shipping
     */
    public function getTotalPrice()
    {
        $subtotal = $this->items()->with('product')->get()->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        return $subtotal + ($this->shipping_cost ?? 0);
    }

    /**
     * Get cart subtotal (without shipping)
     */
    public function getSubtotal()
    {
        return $this->items()->with('product')->get()->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    /**
     * Get total items count
     */
    public function getTotalItems()
    {
        return $this->items()->sum('quantity');
    }
}
