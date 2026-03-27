<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    /**
     * Show forgot password entry form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Check if user exists by email or mobile
     */
    public function checkUser(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $user = User::where('role', 'user')
            ->where(function ($query) use ($request) {
                $query->where('email', $request->identifier)
                      ->orWhere('mobile', $request->identifier);
            })
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Account not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'user_id' => $user->id,
            'email' => $user->email,
            'mobile' => $user->mobile
        ]);
    }

    /**
     * Show reset method selection page
     */
    public function showResetMethodForm($userId)
    {
        $user = User::findOrFail($userId);
        return view('auth.reset-method', ['user' => $user]);
    }

    /**
     * Show old password reset form
     */
    public function showOldPasswordForm($userId)
    {
        $user = User::findOrFail($userId);
        return view('auth.reset-old-password', ['user' => $user]);
    }

    /**
     * Verify old password
     */
    public function verifyOldPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::findOrFail($request->user_id);

        // Check if old password matches
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Old password is incorrect'
            ], 400);
        }

        // Check if new password is same as old
        if (Hash::check($request->new_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'New password must be different from old password'
            ], 400);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully'
        ]);
    }

    /**
     * Show OTP identity verification form
     */
    public function showVerifyIdentityForm($userId)
    {
        $user = User::findOrFail($userId);
        return view('auth.verify-identity', ['user' => $user]);
    }

    /**
     * Verify identity (email OR mobile match)
     */
    public function verifyIdentity(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        
        $email = $request->input('email');
        $mobile = $request->input('mobile');

        \Log::info('Verify Identity Request', [
            'user_id' => $request->user_id,
            'user_email' => $user->email,
            'user_mobile' => $user->mobile,
            'provided_email' => $email,
            'provided_mobile' => $mobile,
        ]);

        // At least one must be provided
        if (!$email && !$mobile) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please provide email or phone number'
            ], 400);
        }

        // Check if at least one identifier matches (case-insensitive for email)
        $emailMatches = $email && strtolower($user->email) === strtolower($email);
        
        // For phone: normalize both by removing non-digits for comparison
        $userMobileDigits = preg_replace('/\D/', '', $user->mobile);
        $providedMobileDigits = preg_replace('/\D/', '', $mobile ?? '');
        $mobileMatches = $mobile && !empty($providedMobileDigits) && $userMobileDigits === $providedMobileDigits;

        \Log::info('Verify Identity Match', [
            'emailMatches' => $emailMatches,
            'mobileMatches' => $mobileMatches,
            'userMobileDigits' => $userMobileDigits,
            'providedMobileDigits' => $providedMobileDigits,
        ]);

        if (!$emailMatches && !$mobileMatches) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email or phone number does not match our records'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Identity verified'
        ]);
    }

    /**
     * Generate OTP code (simulated)
     */
    public function generateOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Generate a random 4-digit code with only EVEN numbers for reference
        $otp = $this->generateEvenNumberOtp();

        return response()->json([
            'status' => 'success',
            'message' => 'Verification code generated',
            'otp_code' => $otp, // Display for reference only
        ]);
    }

    /**
     * Generate a 4-digit OTP with only even numbers (0, 2, 4, 6, 8)
     */
    private function generateEvenNumberOtp()
    {
        $evenNumbers = [0, 2, 4, 6, 8];
        $otp = '';

        for ($i = 0; $i < 4; $i++) {
            $otp .= $evenNumbers[array_rand($evenNumbers)];
        }

        return $otp;
    }

    /**
     * Show OTP input form
     */
    public function showOtpInputForm($userId)
    {
        $user = User::findOrFail($userId);
        return view('auth.verify-otp', ['user' => $user]);
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp_code' => 'required|string|size:4|regex:/^[02468]{4}$/',
        ]);

        $user = User::findOrFail($request->user_id);

        // Valid format - any 4-digit even-number code is accepted
        return response()->json([
            'status' => 'success',
            'message' => 'Verification code verified'
        ]);
    }

    /**
     * Show form to set new password after OTP verification
     */
    public function showNewPasswordForm($userId)
    {
        $user = User::findOrFail($userId);
        return view('auth.reset-otp-password', ['user' => $user]);
    }

    /**
     * Reset password using OTP verification (final step)
     */
    public function resetPasswordWithOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::findOrFail($request->user_id);

        // Check if new password is same as old
        if (Hash::check($request->new_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'New password must be different from old password'
            ], 400);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully'
        ]);
    }

    /**
     * Resend OTP (regenerate)
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Generate new OTP code for reference
        $otp = $this->generateEvenNumberOtp();

        return response()->json([
            'status' => 'success',
            'message' => 'New verification code generated',
            'otp_code' => $otp, // For demo/testing
        ]);
    }
}
