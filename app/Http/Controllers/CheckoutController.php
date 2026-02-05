<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Size;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $platformFee = 27;
        $shipping = $subtotal > 999 ? 0 : 50;
        $total = $subtotal + $platformFee + $shipping;

        // Get user addresses if logged in
        $addresses = collect();
        if (Auth::check()) {
            $addresses = Address::where('user_id', Auth::id())->get();
        }

        return view('shop.checkout-final', compact('cart', 'subtotal', 'shipping', 'total', 'addresses'));
    }

    public function store(Request $request)
    {
        try {
            // Log the incoming request data for debugging
            \Log::info('Checkout store request:', $request->except(['password', 'card_number', 'card_cvv']));
            
            // Validate payment method and basic shipping info
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'mobile' => 'required|string|min:10',
                'address_id' => 'nullable|integer',
                'address' => 'nullable|string',
                'address_line2' => 'nullable|string',
                'city' => 'nullable|string',
                'state' => 'nullable|string',
                'pincode' => 'nullable|string',
                'address_type' => 'required|in:home,office',
                'delivery_time' => 'nullable|in:9-5,10-6,9-1,2-6',
                'payment_method' => 'required|in:cod,upi,debit_card,credit_card,netbanking,wallet',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation errors:', $e->errors());
            throw $e;
        }

        // Validate that either address_id is provided OR new address fields are filled
        $hasAddressId = !empty($request->input('address_id'));
        $hasNewAddress = !empty($request->input('address')) && !empty($request->input('city')) && 
                        !empty($request->input('state')) && !empty($request->input('pincode'));
        
        if (!$hasAddressId && !$hasNewAddress) {
            return redirect()->back()->withErrors([
                'address' => 'Please select a saved address or enter a new delivery address with city, state, and pincode.'
            ])->withInput();
        }

        // Validate payment-specific fields
        $paymentMethod = $request->input('payment_method');
        if ($paymentMethod === 'upi') {
            $request->validate(['upi_id' => 'required|string']);
        } elseif (in_array($paymentMethod, ['debit_card', 'credit_card'])) {
            $request->validate([
                'card_number' => 'required|string',
                'card_expiry' => 'required|string',
                'card_cvv' => 'required|string',
                'card_name' => 'required|string',
            ]);
            
            // Validate card number format (remove spaces and check)
            $cardNumber = preg_replace('/\s+/', '', $request->input('card_number'));
            if (!preg_match('/^\d{16}$/', $cardNumber)) {
                return redirect()->back()->withErrors([
                    'card_number' => 'Card number must be 16 digits.'
                ])->withInput();
            }
            
            // Validate card expiry
            if (!preg_match('/^\d{2}\/\d{2}$/', $request->input('card_expiry'))) {
                return redirect()->back()->withErrors([
                    'card_expiry' => 'Expiry must be in MM/YY format.'
                ])->withInput();
            }
            
            // Validate CVV
            if (!preg_match('/^\d{3,4}$/', $request->input('card_cvv'))) {
                return redirect()->back()->withErrors([
                    'card_cvv' => 'CVV must be 3-4 digits.'
                ])->withInput();
            }
        } elseif ($paymentMethod === 'netbanking') {
            $request->validate(['bank_name' => 'required|string']);
        } elseif ($paymentMethod === 'wallet') {
            $request->validate([
                'wallet_name' => 'required|string',
                'wallet_id' => 'required|string',
            ]);
        }

        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        // Determine shipping address
        $addressId = $request->input('address_id');
        $name = $request->input('first_name') . ' ' . $request->input('last_name');
        $email = $request->input('email');
        $mobile = $request->input('mobile');
        $addressText = '';
        $city = '';
        $state = '';
        $pincode = '';

        if ($addressId) {
            $address = Address::find($addressId);
            if (!$address) {
                return redirect()->back()->withErrors([
                    'address_id' => 'Selected address not found'
                ])->withInput();
            }
            // Combine address fields (address, address_line_1, address_line_2)
            $addressParts = [];
            if (!empty($address->address)) {
                $addressParts[] = $address->address;
            }
            if (!empty($address->address_line_1)) {
                $addressParts[] = $address->address_line_1;
            }
            if (!empty($address->address_line_2)) {
                $addressParts[] = $address->address_line_2;
            }
            $addressText = !empty($addressParts) ? implode(', ', $addressParts) : '';
            
            // Ensure address is not empty
            if (empty($addressText)) {
                return redirect()->back()->withErrors([
                    'address_id' => 'Selected address is incomplete. Please enter a new address.'
                ])->withInput();
            }
            
            $city = $address->city ?? '';
            $state = $address->state ?? '';
            // Use postal_code as fallback if pincode is empty
            $pincode = $address->pincode ?? $address->postal_code ?? '';
            
            // Validate that city, state, and pincode are not empty
            if (empty($city) || empty($state) || empty($pincode)) {
                return redirect()->back()->withErrors([
                    'address_id' => 'Selected address is missing required information (city, state, or pincode). Please enter a new address.'
                ])->withInput();
            }
        } else {
            $addressText = $request->input('address');
            $city = $request->input('city');
            $state = $request->input('state');
            $pincode = $request->input('pincode');
            
            // If user is logged in and entered a new address, save it to their profile
            if (Auth::check() && !empty($addressText) && !empty($city) && !empty($state) && !empty($pincode)) {
                $newAddress = Address::create([
                    'user_id' => Auth::id(),
                    'name' => $name,
                    'phone' => $mobile,
                    'address_line_1' => $addressText,
                    'city' => $city,
                    'state' => $state,
                    'postal_code' => $pincode,
                    'country' => $request->input('country', 'India'),
                    'is_default' => 0,
                ]);
            }
        }

        // Validate stock
        foreach ($cart as $item) {
            if (!empty($item['size']) && $item['size'] !== 'one-size') {
                $sizeRecord = Size::where('product_id', $item['id'])
                    ->where('size', $item['size'])
                    ->first();

                if (!$sizeRecord || $sizeRecord->stock < $item['quantity']) {
                    return redirect()->route('cart.index')->with(
                        'error',
                        "Sorry, {$item['name']} in size {$item['size']} has insufficient stock."
                    );
                }
            } elseif ($item['size'] === 'one-size') {
                // For accessories, check total stock
                $product = \App\Models\Product::find($item['id']);
                $totalStock = $product->getTotalStock();
                
                if ($totalStock < $item['quantity']) {
                    return redirect()->route('cart.index')->with(
                        'error',
                        "Sorry, {$item['name']} has insufficient stock."
                    );
                }
            }
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $platformFee = 27;
        $shipping = $subtotal > 999 ? 0 : 50;
        
        // Calculate coupon discount if applied
        $discount = 0;
        if(session('applied_coupon')) {
            $appliedCoupon = \App\Models\Coupon::where('code', session('applied_coupon'))->first();
            if($appliedCoupon) {
                if($appliedCoupon->type === 'percent') {
                    $discount = round($subtotal * ($appliedCoupon->value / 100));
                } else {
                    $discount = $appliedCoupon->value;
                }
            }
        }
        
        $total = $subtotal + $platformFee + $shipping - $discount;

        $userId = Auth::check() ? Auth::id() : null;

        try {
            DB::beginTransaction();

            // Create order with additional fields
            $orderData = [
                'user_id' => $userId,
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'address' => $addressText,
                'city' => $city,
                'state' => $state,
                'pincode' => $pincode,
                'address_type' => $request->input('address_type'),
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'total_amount' => $total,
            ];

            // Add office-specific details
            if ($request->input('address_type') === 'office') {
                $orderData['delivery_time'] = $request->input('delivery_time');
                $orderData['open_saturday'] = $request->input('open_saturday') ? 1 : 0;
                $orderData['open_sunday'] = $request->input('open_sunday') ? 1 : 0;
            }

            // Add payment details
            if ($paymentMethod === 'upi') {
                $orderData['payment_details'] = json_encode(['upi_id' => $request->input('upi_id')]);
            } elseif (in_array($paymentMethod, ['debit_card', 'credit_card'])) {
                $orderData['payment_details'] = json_encode([
                    'card_last_4' => substr($request->input('card_number'), -4),
                    'card_name' => $request->input('card_name'),
                ]);
            } elseif ($paymentMethod === 'netbanking') {
                $orderData['payment_details'] = json_encode(['bank' => $request->input('bank_name')]);
            } elseif ($paymentMethod === 'wallet') {
                $orderData['payment_details'] = json_encode([
                    'wallet' => $request->input('wallet_name'),
                    'id' => $request->input('wallet_id'),
                ]);
            }

            $order = Order::create($orderData);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'size' => $item['size'] ?? null,
                ]);

                // Decrease stock for the specific size
                if (!empty($item['size']) && $item['size'] !== 'one-size') {
                    $sizeRecord = Size::where('product_id', $item['id'])
                        ->where('size', $item['size'])
                        ->lockForUpdate()
                        ->first();

                    if ($sizeRecord) {
                        $sizeRecord->stock -= $item['quantity'];
                        
                        // Update is_available status
                        $sizeRecord->is_available = $sizeRecord->stock > 0 ? 1 : 0;
                        $sizeRecord->save();
                    }
                } elseif ($item['size'] === 'one-size') {
                    // For accessories, deduct from first available size record
                    $product = \App\Models\Product::find($item['id']);
                    $sizeRecord = $product->sizeVariants()->lockForUpdate()->first();
                    
                    if ($sizeRecord) {
                        $sizeRecord->stock -= $item['quantity'];
                        $sizeRecord->is_available = $sizeRecord->stock > 0 ? 1 : 0;
                        $sizeRecord->save();
                    }
                }
            }

            DB::commit();

            // Clear cart and coupon after order
            session()->forget(['cart', 'cart_count', 'applied_coupon']);

            return redirect()->route('order.success', $order->id);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Order creation failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->withErrors(['general' => 'Failed to process order. Please try again. ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function success($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);
        return view('shop.order-success', compact('order'));
    }
}
