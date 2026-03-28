<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\CouponPricing;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shipping = $subtotal > 999 ? 0 : 50;
        $total = CouponPricing::calculateTotal($subtotal, $shipping, 27, session('applied_coupon'))['total'];

        // Update cart count in session
        $cartCount = array_sum(array_column($cart, 'quantity'));
        session(['cart_count' => $cartCount]);

        return view('shop.cart', compact('cart', 'subtotal', 'shipping', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:admin_products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = session('cart', []);

        $cartKey = $product->id . '_' . ($request->size ?? 'default');

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'size' => $request->size ?? null,
                'quantity' => $request->quantity,
            ];
        }

        session(['cart' => $cart]);
        
        // Update cart count
        $cartCount = array_sum(array_column($cart, 'quantity'));
        session(['cart_count' => $cartCount]);

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function addProduct($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $cart = session('cart', []);

        $quantity = $request->input('quantity', 1);
        $size = $request->input('size', 'M');
        
        // Get the stock for this specific size
        $sizeVariant = $product->sizeVariants()->where('size', $size)->first();
        
        if (!$sizeVariant || $sizeVariant->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => "Sorry! {$size} size is currently out of stock.",
            ], 400);
        }
        
        if ($quantity > $sizeVariant->stock) {
            return response()->json([
                'success' => false,
                'message' => "Sorry! {$product->name} in size {$size} only has {$sizeVariant->stock} items available. You tried to add {$quantity} items.",
            ], 400);
        }
        
        $cartKey = $product->id . '_' . $size;

        if (isset($cart[$cartKey])) {
            $newQuantity = $cart[$cartKey]['quantity'] + $quantity;
            if ($newQuantity > $sizeVariant->stock) {
                $currentQty = $cart[$cartKey]['quantity'];
                $remainingStock = $sizeVariant->stock - $currentQty;
                return response()->json([
                    'success' => false,
                    'message' => "You already have {$currentQty} of this item in your cart. Only {$remainingStock} more can be added.",
                ], 400);
            }
            $cart[$cartKey]['quantity'] = $newQuantity;
        } else {
            $cart[$cartKey] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'size' => $size,
                'quantity' => $quantity,
            ];
        }

        session(['cart' => $cart]);
        
        // Update cart count
        $cartCount = array_sum(array_column($cart, 'quantity'));
        session(['cart_count' => $cartCount]);

        // Return JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Product added to bag!',
            'cart_count' => $cartCount,
        ]);
    }

    public function update(Request $request)
    {
        $cart = session('cart', []);
        $cartKey = $request->cart_key;
        $action = $request->action;
        $removed = false;
        $quantity = 0;
        
        if ($cartKey && isset($cart[$cartKey])) {
            // Get product and stock information
            $product = Product::find($cart[$cartKey]['id']);
            $size = $cart[$cartKey]['size'] ?? null;
            $currentQty = $cart[$cartKey]['quantity'];
            
            // Get stock for this size
            $stock = 0;
            if ($product && $size) {
                $sizeVariant = $product->sizeVariants()->where('size', $size)->first();
                $stock = $sizeVariant ? $sizeVariant->stock : 0;
            }
            
            if ($action === 'increase') {
                // Check if increasing would exceed stock
                if ($currentQty + 1 > $stock) {
                    if ($request->expectsJson() || $request->isJson() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => "⚠️ Only {$stock} available for this size",
                            'removed' => false,
                        ], 400);
                    }
                    return redirect()->route('cart.index')->with('error', "Only {$stock} available for this item");
                }
                $cart[$cartKey]['quantity']++;
            } elseif ($action === 'decrease') {
                $cart[$cartKey]['quantity']--;
                if ($cart[$cartKey]['quantity'] <= 0) {
                    unset($cart[$cartKey]);
                    $removed = true;
                }
            }
            
            if (!$removed && isset($cart[$cartKey])) {
                $quantity = $cart[$cartKey]['quantity'];
            }
        }
        
        session(['cart' => $cart]);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        session(['cart_count' => $cartCount]);
        
        // Calculate summary
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $shipping = $subtotal > 999 ? 0 : 50;
        
        // Calculate coupon discount if applied
        $pricing = CouponPricing::calculateTotal($subtotal, $shipping, 27, session('applied_coupon'));
        $discount = $pricing['discount'];
        $total = $pricing['total'];
        if ($request->expectsJson() || $request->isJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'removed' => $removed,
                'quantity' => $quantity,
                'summary' => [
                    'subtotal' => number_format($subtotal, 2),
                    'shipping' => $shipping,
                    'discount' => number_format($discount, 0),
                    'total' => number_format($total, 2),
                ],
            ]);
        }
        return redirect()->route('cart.index');
    }

    public function remove(Request $request)
    {
        $cart = session('cart', []);
        $cartKey = $request->cart_key;
        if ($cartKey && isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
        }
        session(['cart' => $cart]);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        session(['cart_count' => $cartCount]);
        if ($request->expectsJson() || $request->isJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
            ]);
        }
        return redirect()->route('cart.index');
    }
}
