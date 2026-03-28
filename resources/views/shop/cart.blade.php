@extends('layouts.app')

@section('title', 'Shopping Cart — VEYRON')

@push('styles')
<style>
.cart-layout { display:grid; grid-template-columns:1fr 350px; gap:30px; padding:30px 0; }
.cart-items { background:#fff; border-radius:12px; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,0.08); }
.cart-summary { background:#fff; border-radius:12px; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,0.08); height:fit-content; position:sticky; top:100px; }

.cart-item { display:flex; gap:20px; padding:20px 0; border-bottom:1px solid #eee; }
.cart-item:last-child { border-bottom:none; }
.cart-item img { width:120px; height:150px; object-fit:cover; border-radius:8px; }
.cart-item-info { flex:1; display:flex; flex-direction:column; gap:8px; }
.cart-item-name { font-weight:600; font-size:1.1rem; color:#222; }
.cart-item-meta { color:#888; font-size:0.9rem; }
.cart-item-price { font-weight:700; font-size:1.1rem; }
.cart-item-actions { display:flex; align-items:center; gap:15px; margin-top:auto; }

.qty-control { display:flex; align-items:center; gap:10px; }
.qty-btn { width:32px; height:32px; border:1px solid #ddd; background:#fff; border-radius:6px; cursor:pointer; }
.qty-value { font-weight:600; }

.cart-remove-cross {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    outline: none;
    transition: transform 0.15s;
}
.cart-remove-cross:hover {
    transform: scale(1.15);
}

.summary-row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #eee; }
.summary-total { font-size:1.3rem; font-weight:700; border-bottom:none; }
.summary-row [title="This fee helps us maintain our platform."] { color:#ff3f6c !important; font-weight:600; }

.platform-fee-trigger {
    background: none;
    border: none;
    color: #ff3f6c;
    cursor: pointer;
    font-size: 0.9em;
    font-weight: 600;
    margin-left: 4px;
    padding: 0;
}

.platform-fee-trigger:hover {
    text-decoration: underline;
}

.platform-fee-modal-overlay {
    align-items: center;
    background: rgba(0, 0, 0, 0.28);
    display: none;
    inset: 0;
    justify-content: center;
    padding: 20px;
    position: fixed;
    z-index: 1000;
}

.platform-fee-modal-overlay.is-open {
    display: flex;
}

.platform-fee-modal {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.18);
    max-width: 420px;
    padding: 22px 18px 18px;
    position: relative;
    width: 100%;
}

.platform-fee-modal-title {
    color: #282c3f;
    font-size: 1.75rem;
    font-weight: 500;
    margin: 0 0 14px;
}

.platform-fee-modal-copy {
    color: #535766;
    font-size: 0.98rem;
    line-height: 1.45;
    margin: 0;
}

.platform-fee-modal-footer {
    border-top: 1px solid #f1f1f1;
    color: #535766;
    font-size: 0.98rem;
    line-height: 1.45;
    margin-top: 20px;
    padding-top: 18px;
}

.platform-fee-highlight {
    color: #ff3f6c;
    font-weight: 700;
}

.platform-fee-close {
    align-items: center;
    background: none;
    border: none;
    color: #282c3f;
    cursor: pointer;
    display: inline-flex;
    font-size: 2rem;
    height: 32px;
    justify-content: center;
    padding: 0;
    position: absolute;
    right: 12px;
    top: 10px;
    width: 32px;
}

.checkout-btn {
    width:100%; padding:15px; background:#222; color:#fff; border:none;
    border-radius:8px; font-size:1rem; font-weight:600; cursor:pointer; margin-top:20px;
}
.checkout-btn:hover { background:#444; }

/* Coupon Modal Styles */
.coupon-modal-overlay {
    align-items: center;
    background: rgba(0, 0, 0, 0.4);
    display: none;
    inset: 0;
    justify-content: center;
    padding: 20px;
    position: fixed;
    z-index: 2000;
    animation: fadeIn 0.3s ease-in;
}

.coupon-modal-overlay.is-open {
    display: flex;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        transform: translateY(20px) scale(0.95);
        opacity: 0;
    }
    to {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
}

.coupon-modal {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    max-width: 550px;
    max-height: 90vh;
    padding: 30px;
    position: relative;
    width: 100%;
    overflow-y: auto;
    animation: slideUp 0.3s ease-out;
}

.coupon-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 16px;
}

.coupon-modal-title {
    color: #222;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
}

.coupon-modal-close {
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    font-size: 1.8rem;
    height: 32px;
    justify-content: center;
    padding: 0;
    width: 32px;
    transition: color 0.2s;
}

.coupon-modal-close:hover {
    color: #222;
}

.coupons-grid {
    display: grid;
    gap: 16px;
    margin-bottom: 20px;
}

.coupon-card {
    background: #f9f9f9;
    border: 2px solid #ddd;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 18px;
    position: relative;
    transition: all 0.3s ease;
}

.coupon-card:hover {
    border-color: #222;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.coupon-card.selected {
    background: #f0f0f0;
    border-color: #222;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.coupon-card.disabled {
    cursor: not-allowed;
    opacity: 0.6;
    background: #fafafa;
}

.coupon-card.disabled:hover {
    border-color: #ddd;
    box-shadow: none;
}

.coupon-radio {
    flex-shrink: 0;
    width: 24px;
    height: 24px;
    border: 2px solid #ddd;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.coupon-card.selected .coupon-radio {
    background: #222;
    border-color: #222;
}

.coupon-radio::after {
    content: '';
    width: 8px;
    height: 8px;
    background: #fff;
    border-radius: 50%;
    opacity: 0;
}

.coupon-card.selected .coupon-radio::after {
    opacity: 1;
}

.coupon-card.disabled .coupon-radio {
    cursor: not-allowed;
}

.coupon-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.coupon-code {
    font-weight: 700;
    font-size: 1.1rem;
    color: #222;
    letter-spacing: 1px;
}

.coupon-discount {
    background: #ff3f6c;
    color: #fff;
    font-weight: 700;
    font-size: 0.9rem;
    display: inline-block;
    padding: 4px 10px;
    border-radius: 4px;
    width: fit-content;
}

.coupon-description {
    font-size: 0.9rem;
    color: #555;
}

.coupon-condition {
    font-size: 0.85rem;
    color: #888;
    margin-top: 4px;
}

.coupon-badge {
    position: absolute;
    top: -8px;
    right: 12px;
    background: #ff3f6c;
    color: #fff;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.coupon-ineligible-tooltip {
    position: absolute;
    bottom: 100%;
    right: 0;
    background: #222;
    color: #fff;
    font-size: 0.8rem;
    padding: 8px 12px;
    border-radius: 6px;
    white-space: nowrap;
    margin-bottom: 8px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s;
    z-index: 10;
}

.coupon-card.disabled:hover .coupon-ineligible-tooltip {
    opacity: 1;
}

.coupon-modal-footer {
    display: flex;
    gap: 12px;
    margin-top: 24px;
    border-top: 1px solid #eee;
    padding-top: 20px;
}

.coupon-modal-btn {
    flex: 1;
    padding: 12px 16px;
    font-size: 0.95rem;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.coupon-modal-btn.apply {
    background: #222;
    color: #fff;
}

.coupon-modal-btn.apply:hover:not(:disabled) {
    background: #444;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.coupon-modal-btn.apply:disabled {
    background: #ddd;
    cursor: not-allowed;
}

.coupon-modal-btn.cancel {
    background: #f0f0f0;
    color: #222;
}

.coupon-modal-btn.cancel:hover {
    background: #e0e0e0;
}

.coupon-applied-badge {
    background: #388e3c;
    color: #fff;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 6px 12px;
    border-radius: 6px;
    display: inline-block;
    margin-left: auto;
}

.coupon-error-message {
    background: #ffebee;
    border-left: 4px solid #ff3f6c;
    color: #c62828;
    padding: 12px;
    border-radius: 6px;
    font-size: 0.9rem;
    display: none;
    margin-bottom: 16px;
}

.coupon-error-message.show {
    display: block;
}

.empty-cart { text-align:center; padding:50px; }
.empty-cart a { color:#222; text-decoration:underline; }

@media (max-width: 768px) {
    .cart-layout { grid-template-columns:1fr; }
    .coupon-modal {
        max-width: 90vw;
        padding: 20px;
    }
    .coupon-modal-title {
        font-size: 1.3rem;
    }
    .coupon-card {
        padding: 14px;
        gap: 12px;
    }
}
</style>
@endpush

@section('content')




<div class="container">
    <h1 style="margin-bottom:20px;"></h1>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px; border-radius:8px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(count($cart) > 0)
        <div class="cart-layout">
            <div class="cart-items">
                @foreach($cart as $id => $item)
                    @php
                        // Get product to fetch stock information
                        $product = \App\Models\Product::find($item['id']);
                        $stock = 0;
                        if ($product && !empty($item['size'])) {
                            $sizeVariant = $product->sizeVariants()->where('size', $item['size'])->first();
                            $stock = $sizeVariant ? $sizeVariant->stock : 0;
                        }
                    @endphp
                    <div class="cart-item">
                        <div style="position:relative; width:120px;">
                            <a href="{{ isset($item['id']) ? route('products.show', $item['id']) : '#' }}" style="display:inline-block;">
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width:120px; height:150px; object-fit:cover; border-radius:8px;">
                            </a>
                            <button type="button" class="cart-remove-cross" title="Remove from cart" data-cart-key="{{ $id }}" style="position:absolute; top:-32px; right:-640px;">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11" cy="11" r="11" fill="#f5f5f5"/><path d="M7 7L15 15" stroke="#bbb" stroke-width="2" stroke-linecap="round"/><path d="M15 7L7 15" stroke="#bbb" stroke-width="2" stroke-linecap="round"/></svg>
                            </button>
                        @push('scripts')
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            document.querySelectorAll('.cart-remove-cross').forEach(btn => {
                                btn.addEventListener('click', function(e) {
                                    const cartKey = this.getAttribute('data-cart-key');
                                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                    const cartItem = this.closest('.cart-item');
                                    fetch("{{ route('cart.remove') }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrfToken,
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({ cart_key: cartKey })
                                    })
                                    .then(res => {
                                        if (res.ok) {
                                            cartItem.remove();
                                            // Optionally update cart badge/count here
                                            // If cart is empty, show empty state
                                            if (document.querySelectorAll('.cart-item').length === 0) {
                                                document.querySelector('.cart-items').innerHTML = `<div class="empty-cart"><h2 style=\"font-weight:700;\">Hey, it feels so light!</h2><p style=\"color:rgba(34,34,34,0.5); margin:20px 0; font-size:1.1rem;\">Let's add some items</p><a href=\"{{ route('products.index') }}\" style=\"display:inline-block; margin-top:18px; padding:12px 32px; background:#222; color:#fff; border-radius:8px; font-weight:600; text-decoration:none; font-size:1rem; box-shadow:0 2px 8px rgba(0,0,0,0.08); transition:background 0.2s;\">Add Item</a></div>`;
                                            }
                                        }
                                    });
                                });
                            });
                        });
                        </script>
                        @endpush
                        </div>
                        <div class="cart-item-info">
                            <span class="cart-item-name">{{ $item['name'] }}</span>
                            <span class="cart-item-meta">
                                @if(!empty($item['size'])) Size: {{ $item['size'] }} @endif
                            </span>
                            <span class="cart-item-price">₹{{ number_format($item['price'], 2) }}</span>
                            <div class="cart-item-actions">
                                <div class="qty-control" data-cart-key="{{ $id }}" data-stock="{{ $stock }}" data-product-id="{{ $item['id'] }}" data-size="{{ $item['size'] ?? 'default' }}">
                                    <button type="button" class="qty-btn qty-decrease">-</button>
                                    <span class="qty-value">{{ $item['quantity'] }}</span>
                                    <button type="button" class="qty-btn qty-increase">+</button>
                                </div>
                                <div class="stock-warning" style="color:#dc3545; font-size:0.85rem; display:none; margin-left:10px; white-space:nowrap;"></div>
                                @push('scripts')
                                <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Remove from cart (already present)
                                    document.querySelectorAll('.cart-remove-cross').forEach(btn => {
                                        btn.addEventListener('click', function(e) {
                                            const cartKey = this.getAttribute('data-cart-key');
                                            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                            const cartItem = this.closest('.cart-item');
                                            fetch("{{ route('cart.remove') }}", {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': csrfToken,
                                                    'Accept': 'application/json'
                                                },
                                                body: JSON.stringify({ cart_key: cartKey })
                                            })
                                            .then(res => {
                                                if (res.ok) {
                                                    cartItem.remove();
                                                    if (document.querySelectorAll('.cart-item').length === 0) {
                                                        document.querySelector('.cart-items').innerHTML = `<div class="empty-cart"><h2 style=\"font-weight:700;\">Hey, it feels so light!</h2><p style=\"color:rgba(34,34,34,0.5); margin:20px 0; font-size:1.1rem;\">Let's add some items</p><a href=\"{{ route('products.index') }}\" style=\"display:inline-block; margin-top:18px; padding:12px 32px; background:#222; color:#fff; border-radius:8px; font-weight:600; text-decoration:none; font-size:1rem; box-shadow:0 2px 8px rgba(0,0,0,0.08); transition:background 0.2s;\">Add Item</a></div>`;
                                                    }
                                                    updateOrderSummary();
                                                }
                                            });
                                        });
                                    });

                                    // Quantity update

                                    document.querySelectorAll('.qty-control').forEach(ctrl => {
                                        const cartKey = ctrl.getAttribute('data-cart-key');
                                        const stock = parseInt(ctrl.getAttribute('data-stock'));
                                        const decreaseBtn = ctrl.querySelector('.qty-decrease');
                                        const increaseBtn = ctrl.querySelector('.qty-increase');
                                        const qtyValue = ctrl.querySelector('.qty-value');
                                        const stockWarning = ctrl.parentElement.querySelector('.stock-warning');
                                        
                                        decreaseBtn.addEventListener('click', function(e) {
                                            e.preventDefault();
                                            if (decreaseBtn.disabled) return; // Prevent multiple clicks
                                            
                                            const newQty = parseInt(qtyValue.textContent) - 1;
                                            if (newQty >= 1) {
                                                decreaseBtn.disabled = true;
                                                updateQuantity(cartKey, 'decrease', ctrl, qtyValue, decreaseBtn);
                                                stockWarning.style.display = 'none';
                                            }
                                        });
                                        
                                        increaseBtn.addEventListener('click', function(e) {
                                            e.preventDefault();
                                            if (increaseBtn.disabled) return; // Prevent multiple clicks
                                            
                                            const currentQty = parseInt(qtyValue.textContent);
                                            if (currentQty + 1 > stock) {
                                                stockWarning.style.display = 'block';
                                                stockWarning.innerHTML = `⚠️ Only ${stock} available`;
                                                return;
                                            }
                                            
                                            increaseBtn.disabled = true;
                                            updateQuantity(cartKey, 'increase', ctrl, qtyValue, increaseBtn);
                                            stockWarning.style.display = 'none';
                                        });
                                    });

                                    function updateQuantity(cartKey, action, ctrl, qtyValue, button) {
                                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                        const stockWarning = ctrl.parentElement.querySelector('.stock-warning');
                                        
                                        fetch("{{ route('cart.update') }}", {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': csrfToken,
                                                'Accept': 'application/json'
                                            },
                                            body: JSON.stringify({ cart_key: cartKey, action: action })
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            button.disabled = false; // Re-enable button
                                            
                                            if (data.success) {
                                                if (data.removed) {
                                                    ctrl.closest('.cart-item').remove();
                                                    if (document.querySelectorAll('.cart-item').length === 0) {
                                                        document.querySelector('.cart-items').innerHTML = `<div class=\"empty-cart\"><h2 style=\"font-weight:700;\">Hey, it feels so light!</h2><p style=\"color:rgba(34,34,34,0.5); margin:20px 0; font-size:1.1rem;\">Let's add some items</p><a href=\"{{ route('products.index') }}\" style=\"display:inline-block; margin-top:18px; padding:12px 32px; background:#222; color:#fff; border-radius:8px; font-weight:600; text-decoration:none; font-size:1rem; box-shadow:0 2px 8px rgba(0,0,0,0.08); transition:background 0.2s;\">Add Item</a></div>`;
                                                    }
                                                } else {
                                                    qtyValue.textContent = data.quantity;
                                                    stockWarning.style.display = 'none';
                                                }
                                                updateOrderSummary(data.summary);
                                            } else {
                                                // Show error message if validation fails
                                                if (stockWarning) {
                                                    stockWarning.style.display = 'block';
                                                    stockWarning.innerHTML = data.message || '⚠️ Cannot update quantity';
                                                }
                                            }
                                        })
                                        .catch(err => {
                                            button.disabled = false; // Re-enable button on error
                                            console.error('Error:', err);
                                        });
                                    }

                                    function updateOrderSummary(summary) {
                                        if (!summary) return location.reload(); // fallback
                                        
                                        // Update subtotal - Find and update the Total MRP row
                                        const allRows = document.querySelectorAll('.summary-row');
                                        if (allRows.length > 0) {
                                            // First row is Total MRP
                                            const totalMrpSpans = allRows[0].querySelectorAll('span');
                                            if (totalMrpSpans.length > 1) {
                                                totalMrpSpans[1].textContent = '₹' + summary.subtotal;
                                            }
                                        }
                                        
                                        // Update shipping
                                        for (let i = 0; i < allRows.length; i++) {
                                            const text = allRows[i].textContent;
                                            if (text.includes('Shipping') && !text.includes('Coupon')) {
                                                const spans = allRows[i].querySelectorAll('span');
                                                if (spans.length > 1) {
                                                    spans[spans.length - 1].textContent = summary.shipping > 0 ? '₹' + summary.shipping : 'FREE';
                                                }
                                                break;
                                            }
                                        }
                                        
                                        // Update coupon discount row if it exists
                                        const discountRow = document.getElementById('coupon-discount-row');
                                        if (discountRow && summary.discount) {
                                            const discountSpans = discountRow.querySelectorAll('span');
                                            if (discountSpans.length > 1) {
                                                discountSpans[1].textContent = '- ₹' + summary.discount;
                                            }
                                        }
                                        
                                        // Update total amount
                                        const totalAmountValue = document.getElementById('total-amount-value');
                                        if (totalAmountValue) {
                                            totalAmountValue.textContent = '₹' + summary.total;
                                        }
                                    }
                                });
                                </script>
                                @endpush
                                <!-- Remove button replaced by cross icon above -->
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @php
                $cartPricing = \App\Support\CouponPricing::calculateTotal($subtotal, $shipping, 27, session('applied_coupon'));
            @endphp
            <div class="cart-summary" style="background:#fff; border-radius:12px; padding:24px 20px; box-shadow:0 2px 10px rgba(233,30,99,0.08);">
                <div style="font-size:0.95rem; color:#222; font-weight:700; letter-spacing:0.5px; margin-bottom:18px;">PRICE DETAILS (<span id="cart-item-count">{{ array_sum(array_column($cart, 'quantity')) }}</span> Items)</div>
                <div class="summary-row"><span>Total MRP</span><span>₹{{ number_format($subtotal, 0) }}</span></div>
                <div class="summary-row">
                    <span>Coupon Discount</span>
                    <span>
                        @if(session('applied_coupon'))
                            <span style="color:#388e3c; font-weight:600; display: flex; align-items: center; gap: 8px;">
                                <span>✓ {{ session('applied_coupon') }}</span>
                                <button id="remove-coupon-btn" style="background:none; border:none; color:#999; cursor:pointer; font-size:0.9rem;">Remove</button>
                            </span>
                        @else
                            <button id="show-coupon-modal-btn" style="background:none; border:none; color:#222; text-decoration:underline; cursor:pointer; font-size:0.97em; padding:0;">Apply Coupon</button>
                        @endif
                    </span>
                </div>
                <div class="summary-row"><span>Platform Fee <span style="color:#888; font-size:0.9em; cursor:pointer;" title="This fee helps us maintain our platform.">Know More</span></span><span style="color:#222;">₹27</span></div>
                <div class="summary-row"><span>Shipping</span><span style="color:#222;">{{ $shipping > 0 ? '₹' . number_format($shipping, 0) : 'FREE' }}</span></div>
                <div style="border-top:1px solid #222; margin:18px 0 12px 0;"></div>
                @php
                    $discount = 0;
                    $discountLabel = '';
                    $appliedCoupon = null;
                    if(session('applied_coupon')) {
                        $couponCode = session('applied_coupon');
                        // Special handling for VEYRON10 coupon
                        if($couponCode === 'VEYRON10') {
                            $discount = round($subtotal * 0.1); // 10% discount
                            $discountLabel = '10% off';
                            $appliedCoupon = (object)['code' => 'VEYRON10'];
                        } 
                        // Special handling for VEY70 coupon
                        elseif($couponCode === 'VEY70') {
                            $discount = round($subtotal * 0.15); // 15% discount
                            $discountLabel = '15% off';
                            $appliedCoupon = (object)['code' => 'VEY70'];
                        } 
                        else {
                            $appliedCoupon = \App\Models\Coupon::where('code', $couponCode)->first();
                            if($appliedCoupon) {
                                if($appliedCoupon->type === 'percent') {
                                    $discount = round($subtotal * ($appliedCoupon->value / 100));
                                    $discountLabel = $appliedCoupon->value . '% off';
                                } else {
                                    $discount = $appliedCoupon->value;
                                    $discountLabel = '₹' . number_format($appliedCoupon->value, 0) . ' off';
                                }
                            }
                        }
                    }
                    $discount = $cartPricing['discount'];
                    $discountLabel = $cartPricing['discountLabel'];
                    $appliedCoupon = $discount > 0;
                    $totalAmount = $cartPricing['total'];
                @endphp
                @if($cartPricing['discount'] > 0)
                <div class="summary-row" id="coupon-discount-row">
                    <span style="color:#222;">Coupon Discount ({{ $cartPricing['discountLabel'] }})</span>
                    <span style="color:#222;">- ₹{{ number_format($discount, 0) }}</span>
                </div>
                @endif
                <div class="summary-row summary-total" style="font-size:1.25rem; font-weight:700; color:#000;">
                    <span>Total Amount</span>
                    <span id="total-amount-value">₹{{ number_format($totalAmount, 0) }}</span>
                </div>
                <form action="{{ route('checkout.index') }}" method="get" style="margin:0;">
                    <button type="submit" class="checkout-btn" style="width:100%; background:#000; color:#fff; font-weight:700; font-size:1.08rem; border-radius:8px; margin-top:18px; padding:15px 0; border:none;">PLACE ORDER</button>
                </form>
            </div>
            <div class="platform-fee-modal-overlay" id="platformFeeModal" aria-hidden="true">
                <div class="platform-fee-modal" role="dialog" aria-modal="true" aria-labelledby="platformFeeModalTitle">
                    <button type="button" class="platform-fee-close" id="platformFeeClose" aria-label="Close platform fee details">×</button>
                    <h2 class="platform-fee-modal-title" id="platformFeeModalTitle">Platform Fee</h2>
                    <p class="platform-fee-modal-copy">
                        Fee levied by Veyron to sustain the efficient operations and continuous improvement of the platform, for a hassle-free app experience.
                    </p>
                    <div class="platform-fee-modal-footer">
                        Have any question? Contact <span class="platform-fee-highlight">Team Veyron</span> for help.
                    </div>
                </div>
            </div>
            <!-- Coupon Modal -->
            <div class="coupon-modal-overlay" id="couponModalOverlay" aria-hidden="true">
                <div class="coupon-modal" role="dialog" aria-modal="true" aria-labelledby="couponModalTitle">
                    <div class="coupon-modal-header">
                        <h2 class="coupon-modal-title" id="couponModalTitle">Available Coupons</h2>
                        <button type="button" class="coupon-modal-close" id="couponModalClose" aria-label="Close coupons">×</button>
                    </div>
                    <div class="coupon-error-message" id="couponErrorMessage"></div>
                    <div class="coupons-grid" id="couponsGrid">
                        <!-- Coupons will be loaded here via JavaScript -->
                    </div>
                    <div class="coupon-modal-footer">
                        <button type="button" class="coupon-modal-btn cancel" id="couponCancelBtn">Cancel</button>
                        <button type="button" class="coupon-modal-btn apply" id="couponApplyBtn" disabled>Apply Coupon</button>
                    </div>
                </div>
            </div>
        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const platformFeeModal = document.getElementById('platformFeeModal');
            const platformFeeClose = document.getElementById('platformFeeClose');
            const platformFeeRow = Array.from(document.querySelectorAll('.summary-row')).find(function(row) {
                return row.textContent.includes('Platform Fee');
            });

            if (platformFeeRow && platformFeeModal && platformFeeClose) {
                const platformFeeLabel = platformFeeRow.querySelector('span');

                if (platformFeeLabel) {
                    platformFeeLabel.innerHTML = 'Platform Fee <button type="button" class="platform-fee-trigger" id="platformFeeTrigger">Know More</button>';
                }

                const platformFeeTrigger = document.getElementById('platformFeeTrigger');

                if (platformFeeTrigger) {
                    const openPlatformFeeModal = function() {
                        platformFeeModal.classList.add('is-open');
                        platformFeeModal.setAttribute('aria-hidden', 'false');
                    };

                    const closePlatformFeeModal = function() {
                        platformFeeModal.classList.remove('is-open');
                        platformFeeModal.setAttribute('aria-hidden', 'true');
                    };

                    platformFeeTrigger.addEventListener('click', openPlatformFeeModal);
                    platformFeeClose.addEventListener('click', closePlatformFeeModal);
                    platformFeeModal.addEventListener('click', function(e) {
                        if (e.target === platformFeeModal) {
                            closePlatformFeeModal();
                        }
                    });

                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape' && platformFeeModal.classList.contains('is-open')) {
                            closePlatformFeeModal();
                        }
                    });
                }
            }

            // ===== NEW COUPON MODAL LOGIC =====
            const couponModalOverlay = document.getElementById('couponModalOverlay');
            const couponModalClose = document.getElementById('couponModalClose');
            const couponCancelBtn = document.getElementById('couponCancelBtn');
            const couponApplyBtn = document.getElementById('couponApplyBtn');
            const showCouponModalBtn = document.getElementById('show-coupon-modal-btn');
            const removeCouponBtn = document.getElementById('remove-coupon-btn');
            const couponsGrid = document.getElementById('couponsGrid');
            const couponErrorMessage = document.getElementById('couponErrorMessage');
            let selectedCoupon = null;

            // Open coupon modal
            if (showCouponModalBtn) {
                showCouponModalBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    openCouponModal();
                });
            }

            // Close coupon modal
            function closeCouponModal() {
                couponModalOverlay.classList.remove('is-open');
                couponModalOverlay.setAttribute('aria-hidden', 'true');
                selectedCoupon = null;
                couponErrorMessage.classList.remove('show');
            }

            // Open coupon modal
            function openCouponModal() {
                couponModalOverlay.classList.add('is-open');
                couponModalOverlay.setAttribute('aria-hidden', 'false');
                loadAvailableCoupons();
            }

            // Load coupons from server
            function loadAvailableCoupons() {
                fetch("{{ route('coupon.available') }}")
                    .then(res => res.json())
                    .then(coupons => {
                        renderCoupons(coupons);
                    })
                    .catch(err => console.error('Failed to load coupons:', err));
            }

            // Render coupon cards
            function renderCoupons(coupons) {
                couponsGrid.innerHTML = '';
                coupons.forEach(coupon => {
                    const card = document.createElement('div');
                    card.className = `coupon-card ${!coupon.eligible ? 'disabled' : ''}`;
                    card.dataset.code = coupon.code;
                    card.innerHTML = `
                        <div class="coupon-radio"></div>
                        <div class="coupon-info" style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 10px; justify-content: space-between;">
                                <span class="coupon-code">${coupon.code}</span>
                                <span class="coupon-discount">${coupon.discount} OFF</span>
                            </div>
                            <span class="coupon-description">${coupon.description}</span>
                            <span class="coupon-condition">📍 ${coupon.condition}</span>
                            ${coupon.restriction ? `<span class="coupon-condition">⚡ ${coupon.restriction}</span>` : ''}
                        </div>
                        ${!coupon.eligible && coupon.ineligibleReason ? `<div class="coupon-ineligible-tooltip">${coupon.ineligibleReason}</div>` : ''}
                    `;

                    if (!coupon.eligible) {
                        card.style.cursor = 'not-allowed';
                    } else {
                        card.addEventListener('click', function() {
                            selectCoupon(coupon.code, card);
                        });
                    }

                    couponsGrid.appendChild(card);
                });
            }

            // Select coupon
            function selectCoupon(code, cardElement) {
                // Remove selection from all cards
                document.querySelectorAll('.coupon-card').forEach(card => {
                    card.classList.remove('selected');
                });
                
                // Add selection to clicked card
                cardElement.classList.add('selected');
                selectedCoupon = code;
                couponApplyBtn.disabled = false;
            }

            // Apply coupon
            couponApplyBtn.addEventListener('click', function() {
                if (!selectedCoupon) return;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch("{{ route('coupon.apply') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ code: selectedCoupon })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        closeCouponModal();
                        couponErrorMessage.classList.remove('show');
                        setTimeout(() => window.location.reload(), 500);
                    } else {
                        couponErrorMessage.textContent = data.message || 'Unable to apply coupon.';
                        couponErrorMessage.classList.add('show');
                    }
                })
                .catch(err => {
                    couponErrorMessage.textContent = 'An error occurred while applying the coupon.';
                    couponErrorMessage.classList.add('show');
                });
            });

            // Remove coupon
            if (removeCouponBtn) {
                removeCouponBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    fetch("{{ route('coupon.remove') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    });
                });
            }

            // Modal controls
            couponModalClose.addEventListener('click', closeCouponModal);
            couponCancelBtn.addEventListener('click', closeCouponModal);
            
            couponModalOverlay.addEventListener('click', function(e) {
                if (e.target === couponModalOverlay) {
                    closeCouponModal();
                }
            });

            // Close modal on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && couponModalOverlay.classList.contains('is-open')) {
                    closeCouponModal();
                }
            });

            // Update item count in summary live
            function updateCartItemCount() {
                const count = Array.from(document.querySelectorAll('.qty-value')).reduce((sum, el) => sum + parseInt(el.textContent), 0);
                document.getElementById('cart-item-count').textContent = count;
            }
            document.querySelectorAll('.qty-control .qty-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    setTimeout(updateCartItemCount, 200); // Wait for AJAX update
                });
            });
        });
        </script>
        @endpush
        @php
        // Coupon apply logic
        if (request()->has('apply_coupon') && request('apply_coupon') === 'veyron10') {
            session(['applied_coupon' => 'veyron10']);
            header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
            exit;
        }
        @endphp
        </div>
    @else
        <div class="empty-cart">
            <h2 style="font-weight:700;">Hey, it feels so light!</h2>
            <p style="color:rgba(34,34,34,0.5); margin:20px 0; font-size:1.1rem;">Let's add some items</p>
            <a href="{{ route('products.index') }}" style="display:inline-block; margin-top:18px; padding:12px 32px; background:#222; color:#fff; border-radius:8px; font-weight:600; text-decoration:none; font-size:1rem; box-shadow:0 2px 8px rgba(0,0,0,0.08); transition:background 0.2s;">Add Item</a>
        </div>
    @endif
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</div>
@endsection
