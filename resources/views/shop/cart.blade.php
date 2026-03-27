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

.empty-cart { text-align:center; padding:50px; }
.empty-cart a { color:#222; text-decoration:underline; }

@media (max-width: 768px) {
    .cart-layout { grid-template-columns:1fr; }
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

            <div class="cart-summary" style="background:#fff; border-radius:12px; padding:24px 20px; box-shadow:0 2px 10px rgba(233,30,99,0.08);">
                <div style="font-size:0.95rem; color:#222; font-weight:700; letter-spacing:0.5px; margin-bottom:18px;">PRICE DETAILS (<span id="cart-item-count">{{ array_sum(array_column($cart, 'quantity')) }}</span> Items)</div>
                <div class="summary-row"><span>Total MRP</span><span>₹{{ number_format($subtotal, 0) }}</span></div>
                <div class="summary-row">
                    <span>Coupon Discount</span>
                    <span>
                        <span id="coupon-discount-value" style="color:#222; font-weight:600;">
                            @if(session('applied_coupon'))
                                <span style="color:#222;">Applied</span>
                            @else
                                <a href="#" id="show-coupon-input" style="color:#222; text-decoration:underline; cursor:pointer; font-size:0.97em;">Apply Coupon</a>
                                <span id="coupon-input-container" style="display:none;">
                                    <input type="text" id="coupon-input" placeholder="Enter code" style="width:90px; font-size:0.95em; padding:2px 6px; border:1px solid #222; border-radius:4px; margin-left:6px;">
                                    <button id="apply-coupon-btn" style="background:#222; color:#fff; border:none; border-radius:4px; padding:3px 10px; font-size:0.95em; cursor:pointer; margin-left:4px;">Apply</button>
                                </span>
                            @endif
                        </span>
                    </span>
                </div>
                <div id="coupon-feedback" style="font-size:0.95em; color:#e53935; margin:2px 0 8px 0; display:none;"></div>
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
                        } else {
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
                    $totalAmount = $subtotal + 27 + $shipping - $discount;
                @endphp
                @if($appliedCoupon)
                <div class="summary-row" id="coupon-discount-row">
                    <span style="color:#222;">Coupon Discount ({{ $discountLabel }})</span>
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

            // Coupon input show/hide
            const showCoupon = document.getElementById('show-coupon-input');
            if (showCoupon) {
                showCoupon.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('coupon-input-container').style.display = 'inline-block';
                    showCoupon.style.display = 'none';
                    document.getElementById('coupon-input').focus();
                });
            }
            // Coupon apply logic
            const couponBtn = document.getElementById('apply-coupon-btn');
            if (couponBtn) {
                couponBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const code = document.getElementById('coupon-input').value.trim();
                    const feedback = document.getElementById('coupon-feedback');
                    feedback.style.display = 'none';
                    fetch("{{ route('coupon.validate') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                        },
                        body: JSON.stringify({ code })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            feedback.style.display = 'block';
                            feedback.style.color = '#388e3c';
                            feedback.textContent = 'Coupon applied successfully!';
                            setTimeout(() => window.location.reload(), 700);
                        } else {
                            feedback.style.display = 'block';
                            feedback.style.color = '#e53935';
                            feedback.textContent = data.message || 'Invalid or expired coupon.';
                        }
                    });
                });
            }
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
