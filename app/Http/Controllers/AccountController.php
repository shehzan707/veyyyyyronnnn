<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            session(['redirect_after_login' => route('account.index')]);
            return redirect()->route('login');
        }

        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $addresses = $user->addresses;

        return view('shop.account', compact('user', 'orders', 'addresses'));
    }

    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'mobile' => 'required|digits:10|unique:users,mobile,' . $user->id,
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
                return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
            }
            throw $e;
        }

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists
            if ($user->profile_picture && $user->profile_picture !== '') {
                $oldPath = public_path('uploads/profiles/' . $user->profile_picture);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $file = $request->file('profile_picture');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $filename);
            $validated['profile_picture'] = $filename;
        }

        $user->update($validated);

        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json(['success' => true, 'message' => 'Profile updated successfully!']);
        }

        return redirect()->route('account.index')->with('success', 'Profile updated successfully!');
    }

    public function addAddress(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $validated = $request->validate([
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        if ($validated['is_default'] ?? false) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        Address::create([
            'user_id' => Auth::id(),
            'name' => $user->first_name . ' ' . $user->last_name,
            'phone' => $user->mobile,
            ...$validated
        ]);

        return redirect()->route('account.index')->with('success', 'Address added successfully!');
    }

    public function updateAddress(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $address = Address::findOrFail($id);
        
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        if ($validated['is_default'] ?? false) {
            Address::where('user_id', Auth::id())->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->route('account.index')->with('success', 'Address updated successfully!');
    }

    public function deleteAddress($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $address = Address::findOrFail($id);
        
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->delete();

        return redirect()->route('account.index')->with('success', 'Address deleted successfully!');
    }

    public function orders()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $orders = Order::with('items')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('shop.orders', compact('orders'));
    }

    public function orderView($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::with('items')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('shop.order-view', compact('order'));
    }
}

