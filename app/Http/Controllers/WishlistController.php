<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            session(['redirect_after_login' => route('wishlist.index')]);
            return redirect()->route('login');
        }

        $wishlistItems = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('shop.wishlist', compact('wishlistItems'));
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Please login first'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:admin_products,id',
        ]);

        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Already in wishlist']);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json(['message' => 'Added to wishlist!']);
    }

    public function addProduct($id, Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to add to wishlist'
            ], 401);
        }

        $product = Product::findOrFail($id);

        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->exists();

        if ($exists) {
            // Remove from wishlist if already added
            Wishlist::where('user_id', Auth::id())
                ->where('product_id', $id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Removed from wishlist!',
                'added' => false
            ]);
        }

        // Add to wishlist
        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Added to wishlist!',
            'added' => true
        ]);
    }

    public function remove(Request $request)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->delete();

        return redirect()->route('wishlist.index')->with('success', 'Removed from wishlist.');
    }
}
