<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Order;
use App\Support\CouponPricing;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    /**
     * Get available coupons with eligibility information
     */
    public function getAvailableCoupons(Request $request)
    {
        $cart = session('cart', []);
        
        // Calculate cart subtotal
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Get the current user
        $user = Auth::user();
        $isFirstTimeUser = $this->isFirstTimeUser($user);
        
        $coupons = [
            [
                'code' => 'VEYRON10',
                'discount' => '10%',
                'description' => '10% off on any order',
                'condition' => 'Applicable on any order amount',
                'restriction' => 'Only for first-time users',
                'type' => 'percent',
                'value' => 10,
                'eligible' => $isFirstTimeUser,
                'ineligibleReason' => $isFirstTimeUser ? null : 'This coupon is only for first-time users',
            ],
            [
                'code' => 'VEY70',
                'discount' => '15%',
                'description' => '15% off on premium orders',
                'condition' => 'Applicable only for orders above ₹70,000',
                'restriction' => 'Minimum order value: ₹70,000',
                'type' => 'percent',
                'value' => 15,
                'eligible' => $subtotal > 70000,
                'ineligibleReason' => $subtotal > 70000 ? null : 'Your cart total must be above ₹70,000 to use this coupon',
            ],
        ];
        
        return response()->json($coupons);
    }

    /**
     * Check if user is a first-time buyer
     */
    public function isFirstTimeUser($user)
    {
        if (!$user) {
            return true; // Guests are considered first-time
        }
        
        // Check if user has any completed/delivered orders
        $hasOrders = Order::where('user_id', $user->id)
            ->whereIn('order_status', ['delivered', 'processing', 'shipped', 'out_for_delivery'])
            ->exists();
        
        return !$hasOrders;
    }

    /**
     * Validate coupon with eligibility checks
     */
    public function validateCoupon(Request $request)
    {
        $code = CouponPricing::normalizeCode($request->input('code'));
        $appliedCouponCode = CouponPricing::normalizeCode(session('applied_coupon'));
        
        $cart = session('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $user = Auth::user();
        $isFirstTimeUser = $this->isFirstTimeUser($user);
        
        // Handle special VEYRON10 coupon (10% discount)
        if ($code === 'VEYRON10') {
            // Check eligibility
            if (!$isFirstTimeUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'This coupon is only applicable for first-time users.',
                    'eligible' => false
                ]);
            }
            
            if ($appliedCouponCode === 'VEYRON10') {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon already applied.',
                    'eligible' => true,
                    'alreadyApplied' => true
                ]);
            }
            
            session(['applied_coupon' => 'VEYRON10']);
            return response()->json([
                'success' => true,
                'type' => 'percent',
                'value' => 10,
                'code' => 'VEYRON10',
            ]);
        }
        
        // Handle VEY70 coupon (15% discount for orders above ₹70,000)
        if ($code === 'VEY70') {
            if ($subtotal <= 70000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart total must be above ₹70,000 to use this coupon.',
                    'eligible' => false
                ]);
            }
            
            if ($appliedCouponCode === 'VEY70') {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon already applied.',
                    'eligible' => true,
                    'alreadyApplied' => true
                ]);
            }
            
            session(['applied_coupon' => 'VEY70']);
            return response()->json([
                'success' => true,
                'type' => 'percent',
                'value' => 15,
                'code' => 'VEY70',
            ]);
        }
        
        // Check database coupons
        $coupon = Coupon::whereRaw('UPPER(code) = ?', [$code])->where('is_active', 1)
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->first();
        
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon.',
                'eligible' => false
            ]);
        }
        
        // Prevent re-applying
        if ($appliedCouponCode === CouponPricing::normalizeCode($coupon->code)) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon already applied.',
                'eligible' => true,
                'alreadyApplied' => true
            ]);
        }
        
        session(['applied_coupon' => $coupon->code]);
        return response()->json([
            'success' => true,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'code' => $coupon->code,
        ]);
    }

    /**
     * Apply coupon directly
     */
    public function applyCoupon(Request $request)
    {
        return $this->validateCoupon($request);
    }

    /**
     * Remove applied coupon
     */
    public function removeCoupon(Request $request)
    {
        session()->forget('applied_coupon');
        
        return response()->json([
            'success' => true,
            'message' => 'Coupon removed successfully'
        ]);
    }
}
