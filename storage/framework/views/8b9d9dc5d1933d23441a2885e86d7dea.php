

<?php $__env->startSection('title', 'Checkout — VEYRON'); ?>

<?php $__env->startPush('styles'); ?>
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
    grid-template-columns: 1fr 380px;
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
    gap: 15px;
    margin-bottom: 25px;
    padding-bottom: 25px;
    border-bottom: 2px solid #eee;
}

.bag-item {
    display: flex;
    gap: 15px;
    padding: 12px;
    background: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #eee;
    transition: all 0.3s ease;
}

.bag-item:hover {
    border-color: #222;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.bag-item img {
    width: 90px;
    height: 110px;
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
    font-size: 0.95rem;
    color: #222;
}

.bag-item-meta {
    font-size: 0.85rem;
    color: #888;
}

.bag-item-price {
    font-weight: 700;
    font-size: 1rem;
    color: #222;
}

/* BILLING SECTION */
.billing-section {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}

.billing-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    font-size: 0.95rem;
    border-bottom: 1px solid #eee;
}

.billing-row:last-child {
    border-bottom: none;
}

.billing-label {
    color: #666;
}

.billing-value {
    font-weight: 600;
    color: #222;
}

.billing-row.total {
    font-size: 1.1rem;
    font-weight: 700;
    color: #222;
    border-top: 2px solid #222;
    padding-top: 12px;
    margin-top: 12px;
}

.billing-row.discount {
    color: #4caf50;
}

.billing-row.discount .billing-value {
    color: #4caf50;
}

/* SHIPPING INFO SECTION */
.shipping-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-section {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-section-title {
    font-weight: 600;
    color: #222;
    font-size: 1rem;
    margin-bottom: 10px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    font-size: 0.9rem;
    color: #333;
    margin-bottom: 6px;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 11px 13px;
    border: 1.5px solid #ddd;
    border-radius: 6px;
    font-size: 0.95rem;
    font-family: inherit;
    transition: all 0.3s ease;
}

.form-group input:hover,
.form-group select:hover,
.form-group textarea:hover {
    border-color: #bbb;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #222;
    outline: none;
    box-shadow: 0 0 0 3px rgba(34, 34, 34, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.form-row.full {
    grid-template-columns: 1fr;
}

/* ADDRESS CARDS */
.address-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}

.address-card {
    border: 2px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #fff;
    position: relative;
}

.address-card:hover {
    border-color: #222;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.address-card.selected {
    border-color: #222;
    background: #f5f5f5;
}

.address-card input[type="radio"] {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.address-card-text {
    padding-right: 30px;
    font-size: 0.85rem;
    color: #666;
    line-height: 1.4;
}

/* ADDRESS TYPE SELECTION */
.address-type-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.address-type-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.address-type-option:hover {
    border-color: #bbb;
}

.address-type-option input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background: #FFFFFF;
    border: 2px solid #2E2E2E;
    border-radius: 4px;
    transition: all 0.2s ease-in-out;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.address-type-option input[type="checkbox"]:hover {
    border-color: #000000;
}

.address-type-option input[type="checkbox"]:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(46, 46, 46, 0.2);
}

.address-type-option input[type="checkbox"]:checked {
    background-color: #000000;
    border-color: #000000;
}

.address-type-option input[type="checkbox"]:checked::after {
    content: '✓';
    color: #FFFFFF;
    font-size: 14px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
}

.address-type-label {
    cursor: pointer;
    font-weight: 600;
    color: #333;
    font-size: 1rem;
}

/* DELIVERY TIMING */
.timing-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 10px;
}

.timing-option {
    position: relative;
}

.timing-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.timing-label {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    border: 2px solid #ddd;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 0.9rem;
    color: #666;
}

.timing-option input[type="radio"]:checked + .timing-label {
    border-color: #222;
    background: #222;
    color: #fff;
}

.timing-label:hover {
    border-color: #222;
}

/* WEEKEND OPTIONS */
.weekend-options {
    display: flex;
    gap: 15px;
}

.checkbox-option {
    display: flex;
    align-items: center;
    gap: 8px;
}

.checkbox-option input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background: #FFFFFF;
    border: 2px solid #2E2E2E;
    border-radius: 4px;
    transition: all 0.2s ease-in-out;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.checkbox-option input[type="checkbox"]:hover {
    border-color: #000000;
}

.checkbox-option input[type="checkbox"]:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(46, 46, 46, 0.2);
}

.checkbox-option input[type="checkbox"]:checked {
    background-color: #000000;
    border-color: #000000;
}

.checkbox-option input[type="checkbox"]:checked::after {
    content: '✓';
    color: #FFFFFF;
    font-size: 14px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
}

.checkbox-option label {
    cursor: pointer;
    font-weight: 600;
    color: #333;
}

/* PAYMENT STEP */
.payment-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 12px;
    margin-bottom: 30px;
}

.payment-method {
    border: 2px solid #ddd;
    border-radius: 8px;
    padding: 18px 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    position: relative;
    background: #fff;
}

.payment-method:hover {
    border-color: #222;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.payment-method.selected {
    border-color: #222;
    background: #f5f5f5;
}

.payment-method input[type="radio"] {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.payment-icon {
    font-size: 32px;
    margin-bottom: 8px;
}

.payment-name {
    font-weight: 600;
    color: #222;
    font-size: 0.9rem;
}

.payment-desc {
    font-size: 0.75rem;
    color: #888;
    margin-top: 4px;
}

/* PAYMENT DETAILS FORM */
.payment-details {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: none;
}

.payment-details.active {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 500px;
    }
}

.payment-details h4 {
    margin: 0 0 15px 0;
    font-size: 0.95rem;
    color: #222;
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

.btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-secondary {
    background: #f0f0f0;
    color: #222;
}

.btn-secondary:hover:not(:disabled) {
    background: #e0e0e0;
}

.btn-primary {
    background: #222;
    color: #fff;
}

.btn-primary:hover:not(:disabled) {
    background: #000;
}

.btn-primary:disabled {
    background: #ccc;
    cursor: not-allowed;
    opacity: 0.6;
}

/* SIDEBAR - ENHANCED */
.order-summary h3 {
    font-size: 1.1rem;
    margin-bottom: 20px;
    color: #222;
}

.summary-items {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
    max-height: 300px;
    overflow-y: auto;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
}

.summary-item-name {
    color: #666;
    flex: 1;
}

.summary-item-price {
    font-weight: 600;
    color: #222;
    margin-left: 10px;
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
    font-size: 0.9rem;
    color: #666;
}

.summary-row.total {
    font-size: 1.15rem;
    font-weight: 700;
    color: #222;
    padding-top: 15px;
    border-top: 2px solid #222;
}

.summary-row.discount {
    color: #4caf50;
}

.summary-row.discount .value {
    color: #4caf50;
    font-weight: 600;
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
        grid-template-columns: repeat(2, 1fr);
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
    
    .address-type-group {
        grid-template-columns: 1fr;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="checkout-container">
    
    <!-- PROGRESS INDICATOR -->
    <div class="progress-container">
        <div class="progress-line"></div>
        <div class="progress-step active" data-step="bag">
            <div class="progress-circle">1</div>
            <div class="progress-label">BAG</div>
        </div>
        <div class="progress-step" data-step="shipping">
            <div class="progress-circle">2</div>
            <div class="progress-label">SHIPPING</div>
        </div>
        <div class="progress-step" data-step="payment">
            <div class="progress-circle">3</div>
            <div class="progress-label">PAYMENT</div>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-error">
            <strong>Please fix the errors:</strong>
            <ul style="margin: 10px 0 0 20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-error"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <form id="checkoutForm" action="<?php echo e(route('checkout.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
        <div class="checkout-main">
            
            <!-- MAIN CONTENT -->
            <div class="checkout-content">
                
                <!-- STEP 1: BAG -->
                <div class="step-content active" data-step="bag">
                    <h2 style="margin-bottom: 20px;">Order Review</h2>
                    
                    <div class="bag-items">
                        <?php $__empty_1 = true; $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="bag-item">
                                <img src="<?php echo e(asset($item['image'])); ?>" alt="<?php echo e($item['name']); ?>">
                                <div class="bag-item-info">
                                    <div>
                                        <div class="bag-item-name"><?php echo e($item['name']); ?></div>
                                        <div class="bag-item-meta">
                                            Qty: <?php echo e($item['quantity']); ?>

                                            <?php if($item['size']): ?> | Size: <?php echo e($item['size']); ?> <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="bag-item-price">₹<?php echo e(number_format($item['price'] * $item['quantity'], 2)); ?></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p style="text-align: center; color: #999;">Your cart is empty</p>
                        <?php endif; ?>
                    </div>

                    <!-- BILLING SUMMARY -->
                    <div class="billing-section">
                        <div class="billing-row">
                            <span class="billing-label">Subtotal</span>
                            <span class="billing-value" id="subtotalAmount">₹<?php echo e(number_format($subtotal, 2)); ?></span>
                        </div>
                        <div class="billing-row" id="platformFeeRow">
                            <span class="billing-label">Platform Fee</span>
                            <span class="billing-value" id="platformFeeAmount">₹27</span>
                        </div>
                        <div class="billing-row" id="shippingRow">
                            <span class="billing-label">Shipping</span>
                            <span class="billing-value" id="shippingAmount"><?php echo e($shipping > 0 ? '₹' . number_format($shipping, 2) : 'FREE'); ?></span>
                        </div>
                        <?php
                            $checkoutPricing = \App\Support\CouponPricing::calculateTotal($subtotal, $shipping, 27, session('applied_coupon'));
                            $discount = $checkoutPricing['discount'];
                        ?>
                        <?php if($discount > 0): ?>
                            <div class="billing-row discount">
                                <span class="billing-label">Coupon Discount (<?php echo e($checkoutPricing['discountLabel']); ?>)</span>
                                <span class="billing-value">-₹<?php echo e(number_format($discount, 0)); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="billing-row total">
                            <span>Total Amount</span>
                            <span id="totalAmount">₹<?php echo e(number_format($subtotal + 27 + $shipping - $discount, 2)); ?></span>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: SHIPPING INFO -->
                <div class="step-content" data-step="shipping">
                    <h2 style="margin-bottom: 25px;">Shipping Information</h2>
                    
                    <div class="shipping-form">
                        
                        <!-- CONTACT INFORMATION -->
                        <div class="form-section">
                            <h4 class="form-section-title">Contact Information</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>First Name <span style="color: #dc3545;">*</span></label>
                                    <input type="text" name="first_name" placeholder="John" required value="<?php echo e(old('first_name', Auth::check() ? Auth::user()->first_name : '')); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Last Name <span style="color: #dc3545;">*</span></label>
                                    <input type="text" name="last_name" placeholder="Doe" required value="<?php echo e(old('last_name', Auth::check() ? Auth::user()->last_name : '')); ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Email <span style="color: #dc3545;">*</span></label>
                                    <input type="email" name="email" placeholder="john@example.com" required value="<?php echo e(old('email', Auth::check() ? Auth::user()->email : '')); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Mobile <span style="color: #dc3545;">*</span></label>
                                    <input type="tel" name="mobile" placeholder="+91 XXXXXXXXXX" required value="<?php echo e(old('mobile', Auth::check() ? Auth::user()->mobile : '')); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- SAVED ADDRESSES -->
                        <?php if($addresses->count() > 0): ?>
                            <div class="form-section">
                                <h4 class="form-section-title">📍 Your Saved Addresses</h4>
                                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px;">
                                    <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label style="cursor: pointer;">
                                            <input type="radio" name="address_id" value="<?php echo e($address->id); ?>" <?php if($loop->first): ?> checked <?php endif; ?> style="cursor: pointer; margin-top: 5px;" onchange="document.getElementById('add-new-address-form').style.display='none';">
                                            <div style="border: 2px solid #ddd; border-radius: 8px; padding: 16px; background: white; transition: all 0.3s; cursor: pointer; margin-top: 5px;"
                                                 onmouseover="this.style.borderColor='#0066cc'; this.style.boxShadow='0 4px 12px rgba(0,102,204,0.15)'"
                                                 onmouseout="this.style.borderColor='#ddd'; this.style.boxShadow='none'">
                                                
                                                <!-- Header with Name and Badge -->
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; gap: 8px;">
                                                    <strong style="font-size: 16px; color: #222; font-weight: 700;"><?php echo e($address->name); ?></strong>
                                                    <?php if($address->is_default): ?>
                                                        <span style="background: #4caf50; color: white; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 700; white-space: nowrap;">DEFAULT</span>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Full Address Details -->
                                                <div style="background: linear-gradient(135deg, #f0f4ff 0%, #f9fbff 100%); border-left: 4px solid #0066cc; padding: 12px; border-radius: 6px; margin-bottom: 12px;">
                                                    <div style="color: #333; font-size: 13px; line-height: 1.7; font-weight: 500;">
                                                        <?php if($address->address): ?>
                                                            <div style="margin-bottom: 4px;"><?php echo e($address->address); ?></div>
                                                        <?php endif; ?>
                                                        <?php if($address->address_line_1): ?>
                                                            <div style="margin-bottom: 4px;"><?php echo e($address->address_line_1); ?></div>
                                                        <?php endif; ?>
                                                        <?php if($address->address_line_2): ?>
                                                            <div style="margin-bottom: 6px;"><?php echo e($address->address_line_2); ?></div>
                                                        <?php endif; ?>
                                                        <div style="color: #555; font-weight: 600;"><?php echo e($address->city); ?>, <?php echo e($address->state); ?> - <?php echo e($address->pincode); ?></div>
                                                        <div style="color: #666; font-size: 12px; margin-top: 4px;"><?php echo e($address->country ?? 'India'); ?></div>
                                                    </div>
                                                </div>

                                                <!-- Contact Info -->
                                                <div style="display: flex; flex-direction: column; gap: 6px; font-size: 12px;">
                                                    <div style="color: #0066cc; font-weight: 600;">📱 <?php echo e($address->mobile); ?></div>
                                                    <?php if($address->email): ?>
                                                        <div style="color: #666;">✉️ <?php echo e($address->email); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- ADD NEW ADDRESS SECTION (Always shown, collapsible button) -->
                        <div class="form-section">
                            <?php if($addresses->count() > 0): ?>
                                <div style="text-align: center; margin: 20px 0;">
                                    <button type="button" onclick="toggleNewAddressForm()" style="background: #f5f5f5; border: 2px solid #ddd; color: #333; padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                                        + Add New Address
                                    </button>
                                </div>
                            <?php else: ?>
                                <h4 class="form-section-title">📍 Add Your Delivery Address</h4>
                            <?php endif; ?>
                        </div>

                        <!-- NEW ADDRESS FORM -->
                        <div id="add-new-address-form" class="form-section" style="<?php if($addresses->count() == 0): ?> display: block; <?php else: ?> display: none; <?php endif; ?> margin-top: 20px; padding: 20px; background: #fafafa; border: 2px dashed #ddd; border-radius: 8px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <h4 class="form-section-title" style="margin: 0;">Add New Address</h4>
                                <button type="button" onclick="toggleNewAddressForm()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">✕</button>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Street Address</label>
                                    <input type="text" name="address" placeholder="House no., Building name" value="<?php echo e(old('address')); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Apartment/Suite</label>
                                    <input type="text" name="address_line2" placeholder="Apartment, suite, etc." value="<?php echo e(old('address_line2')); ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" name="city" placeholder="City" value="<?php echo e(old('city')); ?>">
                                </div>
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" name="state" placeholder="State" value="<?php echo e(old('state')); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Pincode</label>
                                <input type="text" name="pincode" placeholder="XXXXXX" value="<?php echo e(old('pincode')); ?>">
                            </div>
                        </div>

                        <!-- ADDRESS TYPE -->
                        <div class="form-section">
                            <h4 class="form-section-title">Address Type</h4>
                            <div class="address-type-group">
                                <div class="address-type-option">
                                    <input type="checkbox" id="home" name="address_type" value="home" checked>
                                    <label for="home" class="address-type-label">🏠 Home</label>
                                </div>
                                <div class="address-type-option">
                                    <input type="checkbox" id="office" name="address_type" value="office">
                                    <label for="office" class="address-type-label">🏢 Office</label>
                                </div>
                            </div>
                        </div>

                        <!-- OFFICE DETAILS (Hidden by default) -->
                        <div class="form-section" id="officeSection" style="display: none;">
                            <h4 class="form-section-title">Office Delivery Details</h4>
                            <div class="form-group">
                                <label>Preferred Delivery Time <span style="color: #dc3545;">*</span></label>
                                <div class="timing-options">
                                    <div class="timing-option">
                                        <input type="radio" id="timing1" name="delivery_time" value="9-5">
                                        <label for="timing1" class="timing-label">9 AM - 5 PM</label>
                                    </div>
                                    <div class="timing-option">
                                        <input type="radio" id="timing2" name="delivery_time" value="10-6">
                                        <label for="timing2" class="timing-label">10 AM - 6 PM</label>
                                    </div>
                                    <div class="timing-option">
                                        <input type="radio" id="timing3" name="delivery_time" value="9-1">
                                        <label for="timing3" class="timing-label">9 AM - 1 PM</label>
                                    </div>
                                    <div class="timing-option">
                                        <input type="radio" id="timing4" name="delivery_time" value="2-6">
                                        <label for="timing4" class="timing-label">2 PM - 6 PM</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Delivery on Weekends</label>
                                <div class="weekend-options">
                                    <div class="checkbox-option">
                                        <input type="checkbox" id="openSat" name="open_saturday">
                                        <label for="openSat">Saturday</label>
                                    </div>
                                    <div class="checkbox-option">
                                        <input type="checkbox" id="openSun" name="open_sunday">
                                        <label for="openSun">Sunday</label>
                                    </div>
                                </div>
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
                            <input type="radio" name="payment_method" value="cod" checked onchange="updatePaymentMethod('cod')">
                            <div class="payment-icon">💵</div>
                            <div class="payment-name">Cash on Delivery</div>
                            <div class="payment-desc">Pay at doorstep</div>
                        </label>

                        <!-- UPI -->
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="upi" onchange="updatePaymentMethod('upi')">
                            <div class="payment-icon">📱</div>
                            <div class="payment-name">UPI</div>
                            <div class="payment-desc">Google Pay, PhonePe</div>
                        </label>

                        <!-- DEBIT CARD -->
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="debit_card" onchange="updatePaymentMethod('debit_card')">
                            <div class="payment-icon">💳</div>
                            <div class="payment-name">Debit Card</div>
                            <div class="payment-desc">Visa, Mastercard</div>
                        </label>

                        <!-- CREDIT CARD -->
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="credit_card" onchange="updatePaymentMethod('credit_card')">
                            <div class="payment-icon">💰</div>
                            <div class="payment-name">Credit Card</div>
                            <div class="payment-desc">Visa, Mastercard</div>
                        </label>

                        <!-- NETBANKING -->
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="netbanking" onchange="updatePaymentMethod('netbanking')">
                            <div class="payment-icon">🏦</div>
                            <div class="payment-name">Net Banking</div>
                            <div class="payment-desc">All banks</div>
                        </label>

                        <!-- WALLET -->
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="wallet" onchange="updatePaymentMethod('wallet')">
                            <div class="payment-icon">👛</div>
                            <div class="payment-name">Digital Wallet</div>
                            <div class="payment-desc">Amazon, Paytm</div>
                        </label>
                    </div>

                    <!-- UPI DETAILS -->
                    <div class="payment-details" id="upiDetails">
                        <h4>UPI Payment Details</h4>
                        <div class="form-group">
                            <label>UPI ID <span style="color: #dc3545;">*</span></label>
                            <input type="text" name="upi_id" placeholder="yourname@bankname" pattern="[a-zA-Z0-9._-]+@[a-zA-Z]+" title="Enter valid UPI ID (e.g., yourname@bankname)">
                        </div>
                    </div>

                    <!-- DEBIT/CREDIT CARD DETAILS -->
                    <div class="payment-details" id="cardDetails">
                        <h4>Card Payment Details</h4>
                        <div class="form-group">
                            <label>Card Number <span style="color: #dc3545;">*</span></label>
                            <input type="text" name="card_number" placeholder="1234 5678 9012 3456" inputmode="numeric" maxlength="19" title="Enter 16-digit card number">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Valid Through <span style="color: #dc3545;">*</span></label>
                                <input type="text" name="card_expiry" placeholder="MM/YY" inputmode="numeric" maxlength="5" pattern="\d{2}/\d{2}" title="Enter in MM/YY format">
                            </div>
                            <div class="form-group">
                                <label>CVV <span style="color: #dc3545;">*</span></label>
                                <input type="text" name="card_cvv" placeholder="123" inputmode="numeric" maxlength="4" pattern="\d{3,4}" title="Enter 3 or 4 digit CVV">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Cardholder Name <span style="color: #dc3545;">*</span></label>
                            <input type="text" name="card_name" placeholder="John Doe">
                        </div>
                    </div>

                    <!-- NET BANKING DETAILS -->
                    <div class="payment-details" id="netbankingDetails">
                        <h4>Net Banking Details</h4>
                        <div class="form-group">
                            <label>Select Bank <span style="color: #dc3545;">*</span></label>
                            <select name="bank_name">
                                <option value="">-- Select your bank --</option>
                                <option value="sbi">State Bank of India (SBI)</option>
                                <option value="icici">ICICI Bank</option>
                                <option value="hdfc">HDFC Bank</option>
                                <option value="axis">Axis Bank</option>
                                <option value="pnb">Punjab National Bank</option>
                                <option value="boi">Bank of India</option>
                                <option value="yes">YES Bank</option>
                                <option value="kotak">Kotak Bank</option>
                                <option value="federal">Federal Bank</option>
                            </select>
                        </div>
                    </div>

                    <!-- WALLET DETAILS -->
                    <div class="payment-details" id="walletDetails">
                        <h4>Digital Wallet Details</h4>
                        <div class="form-group">
                            <label>Select Wallet <span style="color: #dc3545;">*</span></label>
                            <select name="wallet_name">
                                <option value="">-- Select your wallet --</option>
                                <option value="amazon_pay">Amazon Pay</option>
                                <option value="paytm">Paytm</option>
                                <option value="mobikwik">Mobikwik</option>
                                <option value="freecharge">Freecharge</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Wallet ID/Phone <span style="color: #dc3545;">*</span></label>
                            <input type="text" name="wallet_id" placeholder="Your registered phone number">
                        </div>
                    </div>

                    <div style="background: #f0f7ff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #0066cc;">
                        <p style="margin: 0; font-size: 0.9rem; color: #0066cc;">
                            <strong>🔒 Secure Payment:</strong> All payment information is encrypted and secure. Your data is protected with industry-standard SSL encryption.
                        </p>
                    </div>
                </div>

                <!-- STEP ACTIONS -->
                <div class="step-actions">
                    <button type="button" class="btn btn-secondary" id="prevBtn" onclick="previousStep()" style="display: none;">
                        ← Back
                    </button>
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextStep()">
                        Continue to Shipping →
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
                    <div class="summary-items" id="summaryItems">
                        <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="summary-item">
                                <div class="summary-item-name"><?php echo e(substr($item['name'], 0, 20)); ?><?php echo e(strlen($item['name']) > 20 ? '...' : ''); ?> × <?php echo e($item['quantity']); ?></div>
                                <div class="summary-item-price">₹<?php echo e(number_format($item['price'] * $item['quantity'], 2)); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="summary-rows">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>₹<?php echo e(number_format($subtotal, 2)); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Platform Fee</span>
                            <span>₹27</span>
                        </div>
                        <div class="summary-row" id="summaryShipping">
                            <span>Shipping</span>
                            <span><?php echo e($shipping > 0 ? '₹' . number_format($shipping, 2) : 'FREE'); ?></span>
                        </div>
                        <?php
                            $discount = $checkoutPricing['discount'] ?? \App\Support\CouponPricing::calculateTotal($subtotal, $shipping, 27, session('applied_coupon'))['discount'];
                        ?>
                        <?php if($discount > 0): ?>
                            <div class="summary-row discount">
                                <span>Coupon Discount (<?php echo e($checkoutPricing['discountLabel'] ?? \App\Support\CouponPricing::calculateTotal($subtotal, $shipping, 27, session('applied_coupon'))['discountLabel']); ?>)</span>
                                <span class="value">-₹<?php echo e(number_format($discount, 0)); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="summary-row total" style="border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px;">
                            <span>Total Amount</span>
                            <span id="sidebarTotal">₹<?php echo e(number_format($subtotal + 27 + $shipping - $discount, 2)); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let currentStep = 1;
const totalSteps = 3;
const steps = ['bag', 'shipping', 'payment'];
const subtotal = <?php echo e($subtotal); ?>;
const shipping = <?php echo e($shipping); ?>;

function getStepName(step) {
    return steps[step - 1];
}

function goToStep(step) {
    if (step < 1 || step > totalSteps) return;
    
    if (step > currentStep && !validateCurrentStep()) {
        return;
    }
    
    document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
    document.querySelector(`.step-content[data-step="${getStepName(step)}"]`).classList.add('active');
    
    document.querySelectorAll('.progress-step').forEach((el, idx) => {
        el.classList.remove('active', 'completed');
        if (idx + 1 < step) {
            el.classList.add('completed');
        } else if (idx + 1 === step) {
            el.classList.add('active');
        }
    });
    
    document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'block';
    document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'block';
    document.getElementById('submitBtn').style.display = step === totalSteps ? 'block' : 'none';
    
    if (step < totalSteps) {
        const nextName = getStepName(step + 1).charAt(0).toUpperCase() + getStepName(step + 1).slice(1);
        document.getElementById('nextBtn').textContent = `Continue to ${nextName} →`;
    }
    
    currentStep = step;
    window.scrollTo(0, 0);
}

function nextStep() {
    goToStep(currentStep + 1);
}

function previousStep() {
    goToStep(currentStep - 1);
}

function validateCurrentStep() {
    if (currentStep === 2) {
        const firstName = document.querySelector('input[name="first_name"]').value.trim();
        const lastName = document.querySelector('input[name="last_name"]').value.trim();
        const email = document.querySelector('input[name="email"]').value.trim();
        const mobile = document.querySelector('input[name="mobile"]').value.trim();
        
        if (!firstName || !lastName || !email || !mobile) {
            alert('Please fill in all contact information fields');
            return false;
        }
        
        const hasAddress = document.querySelector('input[name="address_id"]:checked') || document.querySelector('input[name="address"]').value.trim();
        if (!hasAddress) {
            alert('Please select or enter a delivery address');
            return false;
        }
        
        if (document.querySelector('input[name="address_type"]:checked').value === 'office') {
            const deliveryTime = document.querySelector('input[name="delivery_time"]:checked');
            if (!deliveryTime) {
                alert('Please select a preferred delivery time for office');
                return false;
            }
        }
    }
    
    if (currentStep === 3) {
        // Validate payment method is selected
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!paymentMethod) {
            alert('Please select a payment method');
            return false;
        }
        
        const method = paymentMethod.value;
        
        // Validate UPI
        if (method === 'upi') {
            const upiId = document.querySelector('input[name="upi_id"]').value.trim();
            if (!upiId) {
                alert('Please enter your UPI ID');
                return false;
            }
            // Simple UPI validation (email@bank format)
            if (!/^[a-zA-Z0-9._-]+@[a-zA-Z]+$/.test(upiId)) {
                alert('Please enter a valid UPI ID (e.g., name@bank)');
                return false;
            }
        }
        
        // Validate Card
        if (method === 'debit_card' || method === 'credit_card') {
            const cardNumber = document.querySelector('input[name="card_number"]').value.replace(/\s/g, '');
            const cardExpiry = document.querySelector('input[name="card_expiry"]').value;
            const cardCVV = document.querySelector('input[name="card_cvv"]').value;
            const cardName = document.querySelector('input[name="card_name"]').value.trim();
            
            if (!cardNumber || cardNumber.length !== 16) {
                alert('Please enter a valid 16-digit card number');
                return false;
            }
            if (!cardExpiry || !/^\d{2}\/\d{2}$/.test(cardExpiry)) {
                alert('Please enter expiry in MM/YY format');
                return false;
            }
            if (!cardCVV || !/^\d{3,4}$/.test(cardCVV)) {
                alert('Please enter a valid CVV (3-4 digits)');
                return false;
            }
            if (!cardName) {
                alert('Please enter cardholder name');
                return false;
            }
        }
        
        // Validate Net Banking
        if (method === 'netbanking') {
            const bankName = document.querySelector('input[name="bank_name"]').value.trim();
            if (!bankName) {
                alert('Please select a bank');
                return false;
            }
        }
        
        // Validate Wallet
        if (method === 'wallet') {
            const walletName = document.querySelector('input[name="wallet_name"]').value.trim();
            const walletId = document.querySelector('input[name="wallet_id"]').value.trim();
            if (!walletName) {
                alert('Please select a wallet');
                return false;
            }
            if (!walletId) {
                alert('Please enter wallet ID/email');
                return false;
            }
        }
    }
    return true;
}

function updatePaymentMethod(method) {
    document.querySelectorAll('.payment-details').forEach(el => el.classList.remove('active'));
    
    if (method === 'upi') {
        document.getElementById('upiDetails').classList.add('active');
    } else if (method === 'debit_card' || method === 'credit_card') {
        document.getElementById('cardDetails').classList.add('active');
    } else if (method === 'netbanking') {
        document.getElementById('netbankingDetails').classList.add('active');
    } else if (method === 'wallet') {
        document.getElementById('walletDetails').classList.add('active');
    }
    
    document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));
    event.target.closest('.payment-method').classList.add('selected');
}

// Address type toggle with mutual exclusion
document.addEventListener('change', function(e) {
    if (e.target.name === 'address_type') {
        // Ensure only one checkbox is checked at a time
        const homeCheckbox = document.getElementById('home');
        const officeCheckbox = document.getElementById('office');
        
        if (e.target.id === 'home' && homeCheckbox.checked) {
            officeCheckbox.checked = false;
        } else if (e.target.id === 'office' && officeCheckbox.checked) {
            homeCheckbox.checked = false;
        }
        
        // Show/hide office section based on selection
        const officeSection = document.getElementById('officeSection');
        if (officeCheckbox.checked) {
            officeSection.style.display = 'block';
        } else {
            officeSection.style.display = 'none';
        }
    }
});

// Toggle New Address Form
function toggleNewAddressForm() {
    const form = document.getElementById('add-new-address-form');
    if (form.style.display === 'none') {
        form.style.display = 'block';
        // Clear address_id selection
        const addressRadios = document.querySelectorAll('input[name="address_id"]');
        addressRadios.forEach(radio => {
            radio.checked = false;
        });
    } else {
        form.style.display = 'none';
        // Clear new address fields
        document.querySelector('input[name="address"]').value = '';
        document.querySelector('input[name="address_line2"]').value = '';
        document.querySelector('input[name="city"]').value = '';
        document.querySelector('input[name="state"]').value = '';
        document.querySelector('input[name="pincode"]').value = '';
    }
}

// Uncheck saved addresses when user starts filling new address form
document.addEventListener('input', function(e) {
    if (['address', 'address_line2', 'city', 'state', 'pincode'].includes(e.target.name)) {
        const addressRadios = document.querySelectorAll('input[name="address_id"]');
        addressRadios.forEach(radio => {
            radio.checked = false;
        });
    }
});

// Card number formatting
document.addEventListener('input', function(e) {
    if (e.target.name === 'card_number') {
        let value = e.target.value.replace(/\s/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    }
    
    if (e.target.name === 'card_expiry') {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        e.target.value = value;
    }
});

// Update submit button total and validate before submit
document.getElementById('submitBtn')?.addEventListener('click', function(e) {
    e.preventDefault();
    
    // Validate payment step before submitting
    if (!validateCurrentStep()) {
        return false;
    }
    
    // Verify all required fields from step 2 are filled
    const firstName = document.querySelector('input[name="first_name"]').value.trim();
    const lastName = document.querySelector('input[name="last_name"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const mobile = document.querySelector('input[name="mobile"]').value.trim();
    
    if (!firstName || !lastName || !email || !mobile) {
        alert('Please go back to Shipping and fill in all contact information');
        goToStep(2);
        return false;
    }
    
    const hasAddress = document.querySelector('input[name="address_id"]:checked') || document.querySelector('input[name="address"]').value.trim();
    if (!hasAddress) {
        alert('Please go back to Shipping and select or enter a delivery address');
        goToStep(2);
        return false;
    }
    
    // Submit the form
    document.getElementById('checkoutForm').submit();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Veyronnnnnnnnnn\resources\views/shop/checkout-final.blade.php ENDPATH**/ ?>