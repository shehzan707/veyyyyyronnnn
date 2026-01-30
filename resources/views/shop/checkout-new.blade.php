@extends('layouts.app')

@section('title', 'Checkout — VEYRON')

@push('styles')
<style>
* { box-sizing: border-box; }

.checkout-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }

/* PROGRESS INDICATOR */
.progress-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 50px;
    position: relative;
}

.progress-line {
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e0e0e0;
    z-index: -1;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    position: relative;
}

.progress-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f0f0f0;
    border: 2px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: #999;
    transition: all 0.3s ease;
    margin-bottom: 8px;
}

.progress-step.active .progress-circle {
    background: #222;
    color: #fff;
    border-color: #222;
    box-shadow: 0 0 0 4px rgba(34, 34, 34, 0.1);
}

.progress-step.completed .progress-circle {
    background: #4caf50;
    color: #fff;
    border-color: #4caf50;
}

.progress-step.completed .progress-circle::after {
    content: '✓';
    font-size: 20px;
}

.progress-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #999;
    text-align: center;
}

.progress-step.active .progress-label {
    color: #222;
}

/* MAIN LAYOUT */
.checkout-main {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 30px;
}

.checkout-content {
    background: #fff;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.checkout-sidebar {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    height: fit-content;
    position: sticky;
    top: 100px;
}

/* STEP CONTENT */
.step-content {
    display: none;
}

.step-content.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* BAG STEP */
.bag-items {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.bag-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    background: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #eee;
}

.bag-item img {
    width: 100px;
    height: 120px;
    object-fit: cover;
    border-radius: 6px;
}

.bag-item-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.bag-item-name {
    font-weight: 600;
    font-size: 1rem;
    color: #222;
}

.bag-item-meta {
    font-size: 0.85rem;
    color: #888;
}

.bag-item-price {
    font-weight: 700;
    font-size: 1.1rem;
    color: #222;
}

/* ADDRESS STEP */
.address-section {
    margin-bottom: 30px;
}

.address-section h4 {
    margin-bottom: 15px;
    font-size: 1rem;
    color: #222;
}

.address-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.address-card {
    border: 2px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.address-card:hover {
    border-color: #222;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.address-card.selected {
    border-color: #222;
    background: #f9f9f9;
}

.address-card input[type="radio"] {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.address-card-content {
    padding-right: 30px;
}

.address-name {
    font-weight: 600;
    margin-bottom: 5px;
    color: #222;
}

.address-text {
    font-size: 0.9rem;
    color: #666;
    line-height: 1.4;
    margin-bottom: 8px;
}

.address-meta {
    font-size: 0.85rem;
    color: #999;
}

.address-badge {
    display: inline-block;
    background: #222;
    color: #fff;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    margin-top: 8px;
}

/* NEW ADDRESS FORM */
.new-address-toggle {
    background: none;
    border: 2px dashed #ddd;
    padding: 15px;
    border-radius: 8px;
    cursor: pointer;
    color: #222;
    font-weight: 600;
    width: 100%;
    text-align: left;
    transition: all 0.3s ease;
}

.new-address-toggle:hover {
    border-color: #222;
    background: #f9f9f9;
}

.new-address-form {
    display: none;
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    margin-top: 15px;
}

.new-address-form.active {
    display: block;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    font-size: 0.9rem;
    color: #333;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.95rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    border-color: #222;
    outline: none;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

/* PAYMENT STEP */
.payment-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
}

.payment-method {
    border: 2px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    position: relative;
}

.payment-method:hover {
    border-color: #222;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.payment-method.selected {
    border-color: #222;
    background: #f9f9f9;
}

.payment-method input[type="radio"] {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.payment-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f0f0;
    border-radius: 8px;
    font-size: 32px;
}

.payment-name {
    font-weight: 600;
    color: #222;
    margin-bottom: 5px;
}

.payment-desc {
    font-size: 0.85rem;
    color: #888;
}

/* BUTTONS */
.step-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn {
    flex: 1;
    padding: 14px 20px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-secondary {
    background: #f0f0f0;
    color: #222;
}

.btn-secondary:hover {
    background: #e0e0e0;
}

.btn-primary {
    background: #222;
    color: #fff;
}

.btn-primary:hover {
    background: #000;
}

.btn-primary:disabled {
    background: #ccc;
    cursor: not-allowed;
}

/* SIDEBAR */
.order-summary h3 {
    font-size: 1.1rem;
    margin-bottom: 20px;
    color: #222;
}

.summary-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    font-size: 0.9rem;
}

.summary-item-name {
    color: #666;
    flex: 1;
}

.summary-item-price {
    font-weight: 600;
    color: #222;
}

.summary-rows {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 15px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.95rem;
    color: #666;
}

.summary-row.total {
    font-size: 1.2rem;
    font-weight: 700;
    color: #222;
    padding-top: 15px;
    border-top: 2px solid #222;
}

/* ALERTS */
.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-error {
    background: #fee;
    color: #c33;
    border: 1px solid #fcc;
}

.alert-success {
    background: #efe;
    color: #3c3;
    border: 1px solid #cfc;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .checkout-main {
        grid-template-columns: 1fr;
    }
    
    .checkout-sidebar {
        position: relative;
        top: 0;
    }
    
    .payment-methods {
        grid-template-columns: 1fr;
    }
    
    .address-list {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .step-actions {
        flex-direction: column;
    }
    
    .checkout-content {
        padding: 20px;
    }
}
</style>
@endpush

@section('content')
<div class="checkout-container">
    
    <!-- PROGRESS INDICATOR -->
    <div class="progress-container">
        <div class="progress-line"></div>
        <div class="progress-step active" data-step="bag">
            <div class="progress-circle">1</div>
            <div class="progress-label">BAG</div>
        </div>
        <div class="progress-step" data-step="address">
            <div class="progress-circle">2</div>
            <div class="progress-label">ADDRESS</div>
        </div>
        <div class="progress-step" data-step="payment">
            <div class="progress-circle">3</div>
            <div class="progress-label">PAYMENT</div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 10px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST">
        @csrf
        
        <div class="checkout-main">
            
            <!-- MAIN CONTENT -->
            <div class="checkout-content">
                
                <!-- STEP 1: BAG -->
                <div class="step-content active" data-step="bag">
                    <h2 style="margin-bottom: 25px;">Order Review</h2>
                    <div class="bag-items">
                        @forelse($cart as $item)
                            <div class="bag-item">
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}">
                                <div class="bag-item-info">
                                    <div>
                                        <div class="bag-item-name">{{ $item['name'] }}</div>
                                        <div class="bag-item-meta">
                                            Qty: {{ $item['quantity'] }}
                                            @if($item['size']) | Size: {{ $item['size'] }} @endif
                                        </div>
                                    </div>
                                    <div class="bag-item-price">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                                </div>
                            </div>
                        @empty
                            <p style="text-align: center; color: #999;">Your cart is empty</p>
                        @endforelse
                    </div>
                </div>

                <!-- STEP 2: ADDRESS -->
                <div class="step-content" data-step="address">
                    <h2 style="margin-bottom: 25px;">Shipping Address</h2>
                    
                    @if($addresses->count() > 0)
                        <div class="address-section">
                            <h4>Select from saved addresses</h4>
                            <div class="address-list">
                                @foreach($addresses as $address)
                                    <label class="address-card @if($loop->first) selected @endif">
                                        <input type="radio" name="address_id" value="{{ $address->id }}" @if($loop->first) checked @endif>
                                        <div class="address-card-content">
                                            <div class="address-name">{{ $address->name }}</div>
                                            <div class="address-text">{{ $address->address }}</div>
                                            <div class="address-text">{{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</div>
                                            <div class="address-meta">📱 {{ $address->mobile }}</div>
                                            @if($address->is_default)
                                                <span class="address-badge">Default</span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="address-section">
                        <button type="button" class="new-address-toggle" onclick="toggleNewAddress()">
                            + Add New Address
                        </button>
                        
                        <div class="new-address-form" id="newAddressForm">
                            <h4 style="margin-bottom: 15px;">New Address</h4>
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="new_name" placeholder="Your full name">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="tel" name="new_mobile" placeholder="+91 XXXXXXXXXX">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="new_email" placeholder="your@email.com">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="new_address" rows="2" placeholder="Street address"></textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" name="new_city" placeholder="City">
                                </div>
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" name="new_state" placeholder="State">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Pincode</label>
                                <input type="text" name="new_pincode" placeholder="XXXXXX">
                            </div>
                            <div class="form-group">
                                <label style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" name="new_default" value="1">
                                    Set as default address
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: PAYMENT -->
                <div class="step-content" data-step="payment">
                    <h2 style="margin-bottom: 25px;">Payment Method</h2>
                    
                    <div class="payment-methods">
                        <!-- COD -->
                        <label class="payment-method selected">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <div class="payment-icon">💵</div>
                            <div class="payment-name">Cash on Delivery</div>
                            <div class="payment-desc">Pay at your doorstep</div>
                        </label>

                        <!-- UPI -->
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="upi">
                            <div class="payment-icon">📱</div>
                            <div class="payment-name">UPI Payment</div>
                            <div class="payment-desc">Google Pay, PhonePe, Paytm</div>
                        </label>

                        <!-- DEBIT CARD -->
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="debit_card">
                            <div class="payment-icon">💳</div>
                            <div class="payment-name">Debit Card</div>
                            <div class="payment-desc">Visa, Mastercard, RuPay</div>
                        </label>

                        <!-- CREDIT CARD -->
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="credit_card">
                            <div class="payment-icon">💰</div>
                            <div class="payment-name">Credit Card</div>
                            <div class="payment-desc">Visa, Mastercard, Amex</div>
                        </label>

                        <!-- NETBANKING -->
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="netbanking">
                            <div class="payment-icon">🏦</div>
                            <div class="payment-name">Net Banking</div>
                            <div class="payment-desc">All major banks</div>
                        </label>

                        <!-- WALLET -->
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="wallet">
                            <div class="payment-icon">👛</div>
                            <div class="payment-name">Digital Wallet</div>
                            <div class="payment-desc">Amazon Pay, Mobikwik</div>
                        </label>
                    </div>

                    <div style="background: #efefef; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <p style="margin: 0; font-size: 0.9rem; color: #666;">
                            <strong>Secure Payment:</strong> Your payment information is encrypted and secure. We use industry-standard SSL encryption.
                        </p>
                    </div>
                </div>

                <!-- STEP ACTIONS -->
                <div class="step-actions">
                    <button type="button" class="btn btn-secondary" id="prevBtn" onclick="previousStep()" style="display: none;">
                        ← Back
                    </button>
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextStep()">
                        Continue to Address →
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn" style="display: none;">
                        Place Order
                    </button>
                </div>
            </div>

            <!-- SIDEBAR -->
            <div class="checkout-sidebar">
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-items">
                        @foreach($cart as $item)
                            <div class="summary-item">
                                <div class="summary-item-name">{{ $item['name'] }} × {{ $item['quantity'] }}</div>
                                <div class="summary-item-price">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="summary-rows">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="summary-row" id="shippingRow">
                            <span>Shipping</span>
                            <span id="shippingAmount">{{ $shipping > 0 ? '₹' . number_format($shipping, 2) : 'FREE' }}</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span id="totalAmount">₹{{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let currentStep = 1;
const totalSteps = 3;

function goToStep(step) {
    if (step < 1 || step > totalSteps) return;
    
    // Validate current step before proceeding
    if (step > currentStep && !validateCurrentStep()) {
        return;
    }
    
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(el => {
        el.classList.remove('active');
    });
    
    // Show selected step
    document.querySelector(`.step-content[data-step="${getStepName(step)}"]`).classList.add('active');
    
    // Update progress
    document.querySelectorAll('.progress-step').forEach((el, idx) => {
        el.classList.remove('active', 'completed');
        if (idx + 1 < step) {
            el.classList.add('completed');
        } else if (idx + 1 === step) {
            el.classList.add('active');
        }
    });
    
    // Update button visibility
    document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'block';
    document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'block';
    document.getElementById('submitBtn').style.display = step === totalSteps ? 'block' : 'none';
    
    // Update button text
    if (step < totalSteps) {
        const nextStepName = getStepName(step + 1);
        document.getElementById('nextBtn').textContent = `Continue to ${nextStepName.charAt(0).toUpperCase() + nextStepName.slice(1)} →`;
    }
    
    currentStep = step;
    window.scrollTo(0, 0);
}

function getStepName(step) {
    const steps = ['bag', 'address', 'payment'];
    return steps[step - 1];
}

function nextStep() {
    goToStep(currentStep + 1);
}

function previousStep() {
    goToStep(currentStep - 1);
}

function validateCurrentStep() {
    if (currentStep === 2) {
        // Validate address selection
        const hasSelectedAddress = document.querySelector('input[name="address_id"]:checked') || 
                                   document.querySelector('input[name="new_address"]:not([placeholder])');
        if (!hasSelectedAddress && !document.querySelector('input[name="new_address"]').value) {
            alert('Please select or add an address');
            return false;
        }
    }
    return true;
}

function toggleNewAddress() {
    const form = document.getElementById('newAddressForm');
    form.classList.toggle('active');
}

// Address card selection
document.addEventListener('change', function(e) {
    if (e.target.name === 'address_id') {
        document.querySelectorAll('.address-card').forEach(card => {
            card.classList.remove('selected');
        });
        e.target.closest('.address-card').classList.add('selected');
    }
    
    // Payment method selection
    if (e.target.name === 'payment_method') {
        document.querySelectorAll('.payment-method').forEach(method => {
            method.classList.remove('selected');
        });
        e.target.closest('.payment-method').classList.add('selected');
    }
});

// Form submission
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    if (!validateCurrentStep()) {
        e.preventDefault();
    }
});
</script>
@endpush
