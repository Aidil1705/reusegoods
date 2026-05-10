<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Toggle wishlist
    public function toggle(Product $product)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Silakan login terlebih dahulu'], 401);
        }

        $user = Auth::user();

        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['status' => 'removed', 'message' => 'Dihapus dari wishlist']);
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            return response()->json(['status' => 'added', 'message' => 'Ditambahkan ke wishlist']);
        }
    }

    // Check if product in wishlist
    public function isInWishlist(Product $product)
    {
        if (!Auth::check()) {
            return response()->json(['inWishlist' => false], 401);
        }

        $user = Auth::user();

        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        return response()->json(['inWishlist' => $exists]);
    }
}
