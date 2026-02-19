<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function validateCoupon(Request $request)
    {
        $code = trim($request->input('code'));
        
        // Handle special VEYRON10 coupon (10% discount)
        if (strtoupper($code) === 'VEYRON10') {
            if (session('applied_coupon') === 'VEYRON10') {
                return response()->json(['success' => false, 'message' => 'Coupon already applied.']);
            }
            session(['applied_coupon' => 'VEYRON10']);
            return response()->json([
                'success' => true,
                'type' => 'percent',
                'value' => 10,
                'code' => 'VEYRON10',
            ]);
        }
        
        $coupon = Coupon::where('code', $code)->where('is_active', 1)
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->first();
        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired coupon.']);
        }
        // Prevent re-applying
        if (session('applied_coupon') === $coupon->code) {
            return response()->json(['success' => false, 'message' => 'Coupon already applied.']);
        }
        session(['applied_coupon' => $coupon->code]);
        return response()->json([
            'success' => true,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'code' => $coupon->code,
        ]);
    }
}
