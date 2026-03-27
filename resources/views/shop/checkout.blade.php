@extends('layouts.app')

@section('title', 'Checkout — VEYRON')

@push('styles')
<style>
.checkout-layout { display: grid; grid-template-columns: 1fr 400px; gap: 30px; padding: 30px 0; }
.checkout-form { background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
.checkout-summary { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); height: fit-content; position: sticky; top: 100px; }

.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
.form-group input, .form-group select, .form-group textarea {
    width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;
    font-size: 1rem; transition: border-color 0.3s;
}
.form-group input:focus, .form-group select:focus { border-color: #222; outline: none; }

.form-group.error input,
.form-group.error select,
.form-group.error textarea { border-color: #dc3545; }

.form-error { color: #dc3545; font-size: 0.85rem; margin-top: 5px; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

.summary-item { display: flex; gap: 15px; padding: 15px 0; border-bottom: 1px solid #eee; }
.summary-item img { width: 60px; height: 80px; object-fit: cover; border-radius: 6px; }
.summary-item-info { flex: 1; }
.summary-item-name { font-weight: 600; font-size: 0.95rem; }
.summary-item-meta { color: #888; font-size: 0.85rem; margin-top: 4px; }

.summary-row { display: flex; justify-content: space-between; padding: 10px 0; }
.summary-total { font-size: 1.2rem; font-weight: 700; border-top: 2px solid #222; margin-top: 10px; padding-top: 15px; }

.payment-options { display: flex; flex-direction: column; gap: 12px; }
.payment-option { display: flex; align-items: center; gap: 12px; padding: 15px; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: 0.3s; }
.payment-option:hover, .payment-option.selected { border-color: #222; }
.payment-option input[type="radio"] { width: 20px; height: 20px; }

.place-order-btn {
    width: 100%; padding: 15px; background: #222; color: #fff; border: none;
    border-radius: 8px; font-size: 1.1rem; font-weight: 600; cursor: pointer; margin-top: 20px;
}
.place-order-btn:hover { background: #444; }

@media (max-width: 768px) {
    .checkout-layout { grid-template-columns: 1fr; }
    .form-row { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="container">
    <h1 style="margin-bottom: 20px;">Checkout</h1>

    @if($errors->any())
        <div style="background:#f8d7da; color:#721c24; padding:15px; border-radius:8px; margin-bottom:20px;">
            <strong>Please fix the following errors:</strong>
            <ul style="margin:10px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div style="background:#f8d7da; color:#721c24; padding:15px; border-radius:8px; margin-bottom:20px;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="checkout-layout">
            <div class="checkout-form">
                <h3 style="margin-bottom: 20px;">Shipping Information</h3>
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Mobile</label>
                        <input type="tel" name="mobile" value="{{ old('mobile', auth()->user()->mobile ?? '') }}" required>
                        @error('mobile')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" rows="3" required>{{ old('address') }}</textarea>
                    @error('address')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" value="{{ old('city') }}" required>
                        @error('city')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" value="{{ old('state') }}" required>
                        @error('state')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Pincode</label>
                    <input type="text" name="pincode" value="{{ old('pincode') }}" required>
                    @error('pincode')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <h3 style="margin: 30px 0 20px;">Payment Method</h3>
                <div class="payment-options">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="cod" {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}>
                        <span>Cash on Delivery (COD)</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="online" {{ old('payment_method') == 'online' ? 'checked' : '' }}>
                        <span>Online Payment (UPI/Cards)</span>
                    </label>
                </div>
                @error('payment_method')<div class="form-error" style="margin-top: 10px;">{{ $message }}</div>@enderror
            </div>

            <div class="checkout-summary">
                <h3 style="margin-bottom: 15px;">Order Summary</h3>
                
                @foreach($cart as $item)
                    <div class="summary-item">
                        <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}">
                        <div class="summary-item-info">
                            <div class="summary-item-name">{{ $item['name'] }}</div>
                            <div class="summary-item-meta">
                                Qty: {{ $item['quantity'] }}
                                @if($item['size']) | Size: {{ $item['size'] }} @endif
                            </div>
                            <div style="font-weight:600; margin-top:5px;">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                        </div>
                    </div>
                @endforeach

                <div class="summary-row" style="margin-top: 15px;">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>{{ $shipping > 0 ? '₹'.number_format($shipping, 2) : 'FREE' }}</span>
                </div>
                <div class="summary-row summary-total">
                    <span>Total</span>
                    <span>₹{{ number_format($total, 2) }}</span>
                </div>

                <button type="submit" class="place-order-btn">Place Order</button>
            </div>
         
        </div>
    </form>
       <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</div>
@endsection
