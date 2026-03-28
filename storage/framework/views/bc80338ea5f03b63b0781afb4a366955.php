

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 800px; margin: 0 auto;">
    <br>
    <h2 style="margin-bottom: 24px; text-align: center;">Order Details</h2>
    <?php if(isset($order)): ?>
        <div style="border:1px solid #ddd; padding:20px; border-radius:8px; margin-bottom:24px; background:#fafafa;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div style="flex:1;">
                    <div style="font-size:18px; font-weight:bold; margin-bottom:8px;">Order #<?php echo e($order->id); ?></div>
                    <div style="margin-bottom:4px;"><strong>Status:</strong> 
                        <span style="padding: 2px 8px; border-radius: 4px; background: #e8f4f8; color: #0066cc;"><?php echo e($order->order_status ?? 'N/A'); ?></span>
                    </div>
                    <div style="margin-bottom:4px;"><strong>Placed on:</strong> <?php echo e($order->created_at->format('d M Y, H:i')); ?></div>
                </div>
                <?php
                    $canCancelOrder = in_array($order->order_status, ['pending', 'processing', 'placed']);
                ?>
                <?php if($canCancelOrder): ?>
                    <button type="button" onclick="openCancelOrderModal()" style="background: #dc3545; color: white; border: none; padding: 10px 16px; border-radius: 4px; cursor: pointer; font-weight: 600; white-space: nowrap; margin-left: 10px;">Cancel Order</button>
                <?php endif; ?>
            </div>
        </div>
        <h4 style="margin-bottom:16px;">Products</h4>
        <div style="overflow-x:auto; min-width: 700px;">
        <table style="min-width:700px; width:100%; border-collapse:separate; border-spacing:0; background:#fff; box-shadow:0 2px 8px #eee; border-radius:10px;">
            <thead>
                <tr style="background:linear-gradient(90deg,#f8fafc,#e2e8f0);">
                    <th style="padding:14px 10px; border-bottom:2px solid #e2e8f0; text-align:left;">Image</th>
                    <th style="padding:14px 10px; border-bottom:2px solid #e2e8f0; text-align:left;">Product</th>
                    <th style="padding:14px 10px; border-bottom:2px solid #e2e8f0; text-align:left;">Size</th>
                    <th style="padding:14px 10px; border-bottom:2px solid #e2e8f0; text-align:right;">Quantity</th>
                    <th style="padding:14px 10px; border-bottom:2px solid #e2e8f0; text-align:right;">Price</th>
                    <th style="padding:14px 10px; border-bottom:2px solid #e2e8f0; text-align:right;">Subtotal</th>
                    <th style="padding:14px 10px; border-bottom:2px solid #e2e8f0; text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isCancelled = ($item->item_status ?? 'Placed') === 'Cancelled';
                        $rowOpacity = $isCancelled ? '0.6' : '1';
                        $canCancel = in_array($item->item_status ?? 'Placed', ['Placed', 'Processing']);
                    ?>
                    <tr style="border-bottom:1px solid #f1f1f1; opacity: <?php echo e($rowOpacity); ?>; background: <?php echo e($isCancelled ? '#f5f5f5' : 'transparent'); ?>;">
                        <td style="padding:12px 10px; text-align:left;">
                            <?php
                                $img = null;
                                if (isset($item->product->image) && $item->product->image) {
                                    $img = $item->product->image;
                                }
                                $imgUrl = $img ? asset($img) : 'https://via.placeholder.com/60x60?text=No+Image';
                                $productUrl = route('products.show', $item->product->id) . '?size=' . urlencode($item->size ?? '') . '&qty=' . ($item->quantity ?? 1);
                            ?>
                            <a href="<?php echo e($productUrl); ?>" style="display:inline-block; text-decoration:none; cursor:pointer;">
                                <img src="<?php echo e($imgUrl); ?>" alt="<?php echo e($item->product->name ?? $item->product_name); ?>" style="width:60px; height:60px; object-fit:cover; border-radius:8px; box-shadow:0 1px 4px #eee; background:#f8f8f8; transition:transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                            </a>
                        </td>
                        <td style="padding:12px 10px;">
                            <div><?php echo e($item->product->name ?? $item->product_name ?? 'N/A'); ?></div>
                            <?php if($isCancelled): ?>
                                <div style="font-size: 12px; color: #dc3545; margin-top: 4px; font-weight: 600;">🚫 Cancelled</div>
                            <?php endif; ?>
                        </td>
                        <td style="padding:12px 10px; text-align:left;"><?php echo e($item->size ?? 'N/A'); ?></td>
                        <td style="padding:12px 10px; text-align:right;"><?php echo e($item->quantity); ?></td>
                        <td style="padding:12px 10px; text-align:right;">₹<?php echo e(number_format($item->price, 0)); ?></td>
                        <td style="padding:12px 10px; text-align:right;">₹<?php echo e(number_format($item->price * $item->quantity, 0)); ?></td>
                        <td style="padding:12px 10px; text-align:center;">
                            <?php if($canCancel): ?>
                                <button type="button" class="cancel-item-btn" data-item-id="<?php echo e($item->id); ?>" data-product-name="<?php echo e($item->product->name ?? $item->product_name); ?>" data-quantity="<?php echo e($item->quantity); ?>" data-refund="₹<?php echo e(number_format($item->price * $item->quantity, 2)); ?>" style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;">Cancel Item</button>
                            <?php else: ?>
                                <span style="font-size: 12px; color: #888;">N/A</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if($isCancelled && $item->cancel_reason): ?>
                        <tr style="border-bottom:1px solid #f1f1f1; background: #fff9f9;">
                            <td colspan="7" style="padding:8px 12px; font-size: 12px; color: #666;">
                                <?php if($item->cancelled_by_admin ?? false): ?>
                                    <strong style="color: #dc3545;">⚠️ Admin Cancelled:</strong> This item was cancelled by our team. Please contact <a href="tel:+91" style="color: #007bff; text-decoration: none;">Veyron Help</a> for assistance.
                                <?php else: ?>
                                    <strong>Cancellation Reason:</strong> <?php echo e($item->cancel_reason); ?>

                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        </div>

        <!-- Bill Section -->
        <div style="margin-top: 40px; padding: 24px; background: #f8f9fa; border-radius: 8px; max-width: 500px; margin-left: auto; margin-right: auto;">
            <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 20px;">Bill</h3>
            
            <!-- Active Order Items -->
            <?php if(isset($activeSubtotal)): ?>
            <?php else: ?>
                <?php
                    $activeSubtotal = 0;
                    $cancelledSubtotal = 0;
                    $orderSubtotal = 0;
                    foreach($order->items as $item) {
                        $isCancelled = ($item->item_status ?? 'Placed') === 'Cancelled';
                        $itemTotal = $item->price * $item->quantity;
                        $orderSubtotal += $itemTotal;
                        if ($isCancelled) {
                            $cancelledSubtotal += $itemTotal;
                        } else {
                            $activeSubtotal += $itemTotal;
                        }
                    }
                    $platformFee = $order->platform_fee ?? 27;
                    $shippingCost = $order->shipping_cost ?? ($orderSubtotal > 999 ? 0 : 50);
                ?>
            <?php endif; ?>
            <?php
                $platformFee = $platformFee ?? ($order->platform_fee ?? 27);
                $orderSubtotal = $orderSubtotal ?? $order->items->sum(function ($item) {
                    return $item->price * $item->quantity;
                });
                $shippingCost = $shippingCost ?? ($order->shipping_cost ?? ($orderSubtotal > 999 ? 0 : 50));
            ?>
            
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $isCancelled = ($item->item_status ?? 'Placed') === 'Cancelled';
                ?>
                <?php if($isCancelled): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: #999; text-decoration: line-through;">
                        <span><?php echo e(substr($item->product->name ?? $item->product_name ?? 'N/A', 0, 35)); ?><?php echo e(strlen($item->product->name ?? $item->product_name ?? 'N/A') > 35 ? '...' : ''); ?> × <?php echo e($item->quantity); ?></span>
                        <span style="font-weight: 500;">₹<?php echo e(number_format($item->price * $item->quantity, 2)); ?></span>
                    </div>
                <?php else: ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: #555;">
                        <span><?php echo e(substr($item->product->name ?? $item->product_name ?? 'N/A', 0, 35)); ?><?php echo e(strlen($item->product->name ?? $item->product_name ?? 'N/A') > 35 ? '...' : ''); ?> × <?php echo e($item->quantity); ?></span>
                        <span style="font-weight: 500;">₹<?php echo e(number_format($item->price * $item->quantity, 2)); ?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <!-- Separator -->
            <div style="border-top: 1px solid #ddd; margin: 16px 0;"></div>

            <!-- Subtotal (Active items only) -->
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: #666;">
                <span>Subtotal</span>
                <span>₹<?php echo e(number_format($activeSubtotal, 2)); ?></span>
            </div>

            <!-- Platform Fee (Only if there are active items) -->
            <?php if($activeSubtotal > 0): ?>
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: #666;">
                    <span>Platform Fee</span>
                    <span>₹<?php echo e(number_format($platformFee, 0)); ?></span>
                </div>
            <?php endif; ?>

            <!-- Shipping (Only if there are active items) -->
            <?php if($activeSubtotal > 0): ?>
                <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 14px; color: #666;">
                    <span>Shipping</span>
                    <span><?php echo e($shippingCost == 0 ? 'FREE' : '₹' . number_format($shippingCost, 2)); ?></span>
                </div>
            <?php endif; ?>

            <!-- Coupon Discount (if applied and there are active items) -->
            <?php if($activeSubtotal > 0 && $order->coupon_code && $order->discount_amount > 0): ?>
                <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 14px; color: #666;">
                    <span>Coupon Discount (<?php echo e($order->coupon_code); ?>)</span>
                    <span>- ₹<?php echo e(number_format($order->discount_amount, 0)); ?></span>
                </div>
            <?php endif; ?>

            <!-- Separator -->
            <div style="border-top: 1px solid #ddd; margin: 12px 0;"></div>

            <!-- Total Amount (Based on active items) -->
            <div style="display: flex; justify-content: space-between; padding-top: 8px;">
                <span style="font-size: 16px; font-weight: 700;">Total Amount</span>
                <span style="font-size: 18px; font-weight: 700;">₹<?php echo e(number_format($activeSubtotal + ($activeSubtotal > 0 ? $platformFee : 0) + ($activeSubtotal > 0 ? $shippingCost : 0) - ($activeSubtotal > 0 ? ($order->discount_amount ?? 0) : 0), 2)); ?></span>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-danger">Order not found.</div>
    <?php endif; ?>
</div>

<!-- Cancellation Modal -->
<div id="cancellationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto; padding: 0;">
        
        <!-- Modal Header -->
        <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; background: #f8f9fa;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 700;">Cancel Item</h3>
            <button type="button" onclick="closeModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">×</button>
        </div>

        <!-- Modal Content -->
        <div style="padding: 24px;">
            
            <!-- Step 1: Reason Selection -->
            <div id="reasonSelectionStep" style="">
                <h4 style="font-size: 16px; margin-bottom: 16px;">Why are you cancelling this item?</h4>
                
                <div id="reasonsList" style="margin-bottom: 20px;">
                    <!-- Reasons will be populated by JavaScript -->
                </div>

                <!-- Custom reason text input (hidden by default) -->
                <div id="customReasonContainer" style="display: none; margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Please specify the reason</label>
                    <textarea id="customReasonText" placeholder="Enter your reason..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: sans-serif; resize: vertical; min-height: 80px; box-sizing: border-box;"></textarea>
                </div>
            </div>

            <!-- Step 2: Confirmation Summary -->
            <div id="confirmationStep" style="display: none;">
                <h4 style="font-size: 16px; margin-bottom: 16px;">Confirm Cancellation</h4>
                
                <div style="background: #f8f9fa; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                    <div style="margin-bottom: 12px;">
                        <span style="color: #666; font-size: 14px;">Product:</span>
                        <div style="font-weight: 600; font-size: 15px;" id="confirmProductName"></div>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <span style="color: #666; font-size: 14px;">Quantity:</span>
                        <div style="font-weight: 600; font-size: 15px;" id="confirmQuantity"></div>
                    </div>
                    <div style="border-top: 1px solid #ddd; padding-top: 12px; margin-top: 12px;">
                        <span style="color: #666; font-size: 14px;">Refund Amount:</span>
                        <div style="font-weight: 700; font-size: 18px; color: #dc3545;" id="confirmRefund"></div>
                    </div>
                </div>

                <div style="background: #e8f5e9; padding: 12px; border-radius: 4px; margin-bottom: 16px; font-size: 14px; color: #2e7d32;">
                    <strong>Note:</strong> <span id="refundNote"></span>
                </div>

                <div style="background: #fff3cd; padding: 12px; border-radius: 4px; margin-bottom: 16px; font-size: 13px; color: #856404;">
                    Your selected reason: <strong id="selectedReasonDisplay"></strong>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div style="padding: 16px 24px; border-top: 1px solid #eee; display: flex; gap: 12px; justify-content: flex-end; background: #f8f9fa;">
            <button type="button" onclick="closeModal()" style="background: #e0e0e0; color: #333; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: 600;">Cancel</button>
            <button type="button" id="proceedBtn" onclick="proceedToConfirmation()" style="background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: 600; opacity: 0.5; cursor: not-allowed;" disabled>Proceed</button>
            <button type="button" id="confirmBtn" onclick="confirmCancellation()" style="display: none; background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: 600;">Confirm Cancel</button>
        </div>
    </div>
</div>

<br><br><br><br><br><br><br><br>

<!-- Cancel Order Modal -->
<div id="cancelOrderModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto; padding: 0;">
        
        <!-- Modal Header -->
        <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; background: #f8f9fa;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 700;">Cancel Entire Order</h3>
            <button type="button" onclick="closeCancelOrderModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">×</button>
        </div>

        <!-- Modal Content -->
        <div style="padding: 24px;">
            <!-- Step 1: Reason Selection -->
            <div id="orderReasonSelectionStep" style="">
                <h4 style="font-size: 16px; margin-bottom: 16px;">Why are you cancelling this order?</h4>
                
                <div id="orderReasonsList" style="margin-bottom: 20px;">
                    <!-- Reasons will be populated by JavaScript -->
                </div>

                <!-- Custom reason text input (hidden by default) -->
                <div id="orderCustomReasonContainer" style="display: none; margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Please specify the reason</label>
                    <textarea id="orderCustomReasonText" placeholder="Enter your reason..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: sans-serif; resize: vertical; min-height: 80px; box-sizing: border-box;"></textarea>
                </div>
            </div>

            <!-- Step 2: Confirmation Summary -->
            <div id="orderConfirmationStep" style="display: none;">
                <h4 style="font-size: 16px; margin-bottom: 16px;">Confirm Order Cancellation</h4>
                
                <div style="background: #f8f9fa; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                    <div style="margin-bottom: 12px;">
                        <span style="color: #666; font-size: 14px;">Total Items:</span>
                        <div style="font-weight: 600; font-size: 15px;" id="confirmOrderItemCount"></div>
                    </div>
                    <div style="border-top: 1px solid #ddd; padding-top: 12px; margin-top: 12px;">
                        <span style="color: #666; font-size: 14px;">Refund Amount:</span>
                        <div style="font-weight: 700; font-size: 18px; color: #28a745;" id="confirmOrderRefund"></div>
                    </div>
                </div>

                <div style="background: #e8f5e9; padding: 12px; border-radius: 4px; margin-bottom: 16px; font-size: 14px; color: #2e7d32;">
                    <strong>Note:</strong> <span id="orderRefundNote"></span>
                </div>

                <div style="background: #fff3cd; padding: 12px; border-radius: 4px; margin-bottom: 16px; font-size: 13px; color: #856404;">
                    Your selected reason: <strong id="orderSelectedReasonDisplay"></strong>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div style="padding: 16px 24px; border-top: 1px solid #eee; display: flex; gap: 12px; justify-content: flex-end; background: #f8f9fa; flex-wrap: nowrap;">
            <button type="button" onclick="closeCancelOrderModal()" style="background: #e0e0e0; color: #333; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: 600; white-space: nowrap;">Cancel</button>
            <button type="button" id="orderProceedBtn" onclick="proceedToOrderConfirmation()" style="background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: 600; opacity: 0.5; cursor: not-allowed; white-space: nowrap;" disabled>Proceed</button>
            <button type="button" id="orderConfirmBtn" onclick="confirmOrderCancellation()" style="display: none; background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: 600; white-space: nowrap;">Confirm Cancellation</button>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let selectedReason = null;
let selectedItemId = null;
let itemDetails = null;

const cancellationReasons = {
    'ordered_by_mistake': 'Ordered by mistake',
    'found_cheaper': 'Found cheaper elsewhere',
    'delivery_time_long': 'Delivery time is too long',
    'wrong_size': 'Wrong size / variant selected',
    'changed_mind': 'Changed my mind',
    'shipping_too_high': 'Shipping charges too high',
    'payment_issue': 'Payment issue',
    'product_unclear': 'Product details not clear',
    'other': 'Other (please specify)',
};

// Fetch cancellation reasons from server
fetch('/order/cancellation-reasons')
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            renderReasons(data.reasons);
        }
    })
    .catch(err => console.error('Error fetching reasons:', err));

function renderReasons(reasons) {
    const reasonsList = document.getElementById('reasonsList');
    reasonsList.innerHTML = '';

    Object.entries(reasons).forEach(([key, label]) => {
        const div = document.createElement('div');
        div.style.cssText = 'margin-bottom: 10px;';
        
        const input = document.createElement('input');
        input.type = 'radio';
        input.name = 'cancel_reason';
        input.value = key;
        input.id = 'reason_' + key;
        input.style.cssText = 'margin-right: 10px; cursor: pointer;';
        input.onchange = () => onReasonChange(key);

        const label_el = document.createElement('label');
        label_el.htmlFor = 'reason_' + key;
        label_el.textContent = label;
        label_el.style.cssText = 'cursor: pointer; font-size: 14px;';

        div.appendChild(input);
        div.appendChild(label_el);
        reasonsList.appendChild(div);
    });
}

function onReasonChange(reasonKey) {
    selectedReason = reasonKey;
    
    // Show custom reason input if "other" is selected
    const customContainer = document.getElementById('customReasonContainer');
    if (reasonKey === 'other') {
        customContainer.style.display = 'block';
        document.getElementById('customReasonText').focus();
    } else {
        customContainer.style.display = 'none';
        document.getElementById('customReasonText').value = '';
    }

    // Enable proceed button
    updateProceedButton();
}

function updateProceedButton() {
    const proceedBtn = document.getElementById('proceedBtn');
    
    if (!selectedReason) {
        proceedBtn.disabled = true;
        proceedBtn.style.opacity = '0.5';
        proceedBtn.style.cursor = 'not-allowed';
        return;
    }

    if (selectedReason === 'other') {
        const customText = document.getElementById('customReasonText').value.trim();
        if (customText) {
            proceedBtn.disabled = false;
            proceedBtn.style.opacity = '1';
            proceedBtn.style.cursor = 'pointer';
        } else {
            proceedBtn.disabled = true;
            proceedBtn.style.opacity = '0.5';
            proceedBtn.style.cursor = 'not-allowed';
        }
    } else {
        proceedBtn.disabled = false;
        proceedBtn.style.opacity = '1';
        proceedBtn.style.cursor = 'pointer';
    }
}

// Listen to custom reason text input
document.addEventListener('input', function(e) {
    if (e.target.id === 'customReasonText') {
        updateProceedButton();
    }
});

function openCancellationModal(btnElement) {
    selectedItemId = btnElement.dataset.itemId;
    itemDetails = {
        id: btnElement.dataset.itemId,
        productName: btnElement.dataset.productName,
        quantity: btnElement.dataset.quantity,
        refund: btnElement.dataset.refund,
    };

    // Reset form
    selectedReason = null;
    document.getElementById('customReasonText').value = '';
    document.querySelectorAll('input[name="cancel_reason"]').forEach(el => el.checked = false);
    
    // Show reason selection step
    document.getElementById('reasonSelectionStep').style.display = 'block';
    document.getElementById('confirmationStep').style.display = 'none';
    document.getElementById('proceedBtn').style.display = 'inline-block';
    document.getElementById('confirmBtn').style.display = 'none';
    updateProceedButton();

    // Show modal
    document.getElementById('cancellationModal').style.display = 'flex';
}

function proceedToConfirmation() {
    if (!selectedReason) {
        alert('Please select a cancellation reason');
        return;
    }

    if (selectedReason === 'other') {
        const customText = document.getElementById('customReasonText').value.trim();
        if (!customText) {
            alert('Please specify your reason');
            return;
        }
    }

    // Show confirmation
    document.getElementById('reasonSelectionStep').style.display = 'none';
    document.getElementById('confirmationStep').style.display = 'block';
    document.getElementById('proceedBtn').style.display = 'none';
    document.getElementById('confirmBtn').style.display = 'inline-block';

    // Populate confirmation details
    document.getElementById('confirmProductName').textContent = itemDetails.productName;
    document.getElementById('confirmQuantity').textContent = itemDetails.quantity + ' unit(s)';
    document.getElementById('confirmRefund').textContent = itemDetails.refund;
    
    const paymentMethod = '<?php echo e($order->payment_method ?? "cod"); ?>';
    if (paymentMethod !== 'cod') {
        document.getElementById('refundNote').textContent = 'Refund will be processed to your original payment method within 5-7 business days.';
    } else {
        document.getElementById('refundNote').textContent = 'This is a Cash on Delivery order. No refund processing needed.';
    }

    const selectedReasonLabel = cancellationReasons[selectedReason] || selectedReason;
    document.getElementById('selectedReasonDisplay').textContent = selectedReasonLabel;
}

function confirmCancellation() {
    if (!selectedItemId || !selectedReason) {
        alert('Please select a cancellation reason');
        return;
    }

    const customReason = selectedReason === 'other' ? document.getElementById('customReasonText').value.trim() : '';

    // Show loading state
    const confirmBtn = document.getElementById('confirmBtn');
    confirmBtn.textContent = 'Processing...';
    confirmBtn.disabled = true;

    // Send cancellation request
    fetch(`/order/item/${selectedItemId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({
            cancel_reason: selectedReason,
            custom_reason: customReason
        })
    })
    .then(res => res.json().catch(() => ({ success: false, message: 'Server error' })))
    .then(data => {
        if (data && data.success) {
            showSuccessNotification('✓ Item cancelled successfully!');
            closeModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            alert('Error: ' + (data?.message || 'Could not cancel item'));
            confirmBtn.textContent = 'Confirm Cancel';
            confirmBtn.disabled = false;
        }
    })
    .catch(() => {
        alert('Network error. Please try again.');
        confirmBtn.textContent = 'Confirm Cancel';
        confirmBtn.disabled = false;
    });
}

function showSuccessNotification(message) {
    // Create success notification
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 20px 24px;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        font-size: 15px;
        font-weight: 600;
        z-index: 10001;
        max-width: 400px;
        animation: slideInRight 0.4s ease-out;
        display: flex;
        align-items: center;
        gap: 12px;
    `;
    
    notification.innerHTML = `
        <span style="font-size: 20px;">✓</span>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 4 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.4s ease-in';
        setTimeout(() => notification.remove(), 400);
    }, 3600);
}

function closeModal() {
    document.getElementById('cancellationModal').style.display = 'none';
    selectedReason = null;
    selectedItemId = null;
}

// Add animation styles if not already present
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Attach event listeners to all cancel buttons
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cancel-item-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            openCancellationModal(this);
        });
    });
});

// Close modal when clicking outside
document.getElementById('cancellationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// ========== CANCEL ORDER FUNCTIONS ==========
let selectedOrderReason = null;
let orderCancellationReasons = {};

// Load cancellation reasons
function loadOrderCancellationReasons() {
    fetch('/order/cancellation-reasons')
        .then(res => res.json())
        .then(data => {
            if (data.success && data.reasons) {
                orderCancellationReasons = data.reasons;
                populateOrderReasonsList();
            }
        })
        .catch(err => console.error('Failed to load reasons:', err));
}

function populateOrderReasonsList() {
    const reasonsList = document.getElementById('orderReasonsList');
    if (!reasonsList) return;
    
    reasonsList.innerHTML = '';
    Object.entries(orderCancellationReasons).forEach(([key, label]) => {
        const container = document.createElement('div');
        container.style.marginBottom = '12px';
        
        const radio = document.createElement('input');
        radio.type = 'radio';
        radio.name = 'order_cancel_reason';
        radio.value = key;
        radio.id = 'order_reason_' + key;
        radio.onchange = () => {
            selectedOrderReason = key;
            document.getElementById('orderCustomReasonContainer').style.display = key === 'other' ? 'block' : 'none';
            updateOrderProceedButton();
        };
        
        const labelEl = document.createElement('label');
        labelEl.htmlFor = 'order_reason_' + key;
        labelEl.style.display = 'flex';
        labelEl.style.alignItems = 'center';
        labelEl.style.cursor = 'pointer';
        labelEl.style.userSelect = 'none';
        labelEl.style.marginLeft = '10px';
        labelEl.textContent = label;
        
        container.appendChild(radio);
        container.appendChild(labelEl);
        reasonsList.appendChild(container);
    });
}

function updateOrderProceedButton() {
    const btn = document.getElementById('orderProceedBtn');
    if (!btn) return;
    
    const customText = document.getElementById('orderCustomReasonText')?.value?.trim() || '';
    const isValid = selectedOrderReason && (selectedOrderReason !== 'other' || customText.length > 0);
    
    btn.disabled = !isValid;
    btn.style.opacity = isValid ? '1' : '0.5';
    btn.style.cursor = isValid ? 'pointer' : 'not-allowed';
}

function openCancelOrderModal() {
    selectedOrderReason = null;
    document.getElementById('cancelOrderModal').style.display = 'flex';
    document.getElementById('orderReasonSelectionStep').style.display = 'block';
    document.getElementById('orderConfirmationStep').style.display = 'none';
    document.getElementById('orderProceedBtn').style.display = 'block';
    document.getElementById('orderConfirmBtn').style.display = 'none';
    document.getElementById('orderCustomReasonContainer').style.display = 'none';
    document.querySelectorAll('input[name="order_cancel_reason"]').forEach(el => el.checked = false);
    document.getElementById('orderCustomReasonText').value = '';
    
    if (Object.keys(orderCancellationReasons).length === 0) {
        loadOrderCancellationReasons();
    } else {
        populateOrderReasonsList();
    }
    
    updateOrderProceedButton();
}

function closeCancelOrderModal() {
    document.getElementById('cancelOrderModal').style.display = 'none';
    selectedOrderReason = null;
}

function proceedToOrderConfirmation() {
    if (!selectedOrderReason) return;
    
    const reasonLabel = orderCancellationReasons[selectedOrderReason] || selectedOrderReason;
    const customText = document.getElementById('orderCustomReasonText').value.trim();
    const displayReason = selectedOrderReason === 'other' && customText ? customText : reasonLabel;
    
    document.getElementById('orderSelectedReasonDisplay').textContent = displayReason;
    
    // Calculate totals from bill section
    let activeTotal = 0;
    const billText = document.body.textContent;
    
    // Extract active subtotal
    const subtotalMatch = billText.match(/Subtotal\s*₹([\d,.]+)/);
    const subtotal = subtotalMatch ? parseFloat(subtotalMatch[1].replace(/,/g, '')) : 0;
    
    // Extract platform fee
    const platformFeeMatch = billText.match(/Platform Fee\s*₹([\d,.]+)/);
    const platformFee = platformFeeMatch ? parseFloat(platformFeeMatch[1].replace(/,/g, '')) : 0;
    
    // Extract shipping
    const shippingMatch = billText.match(/Shipping\s*(FREE|₹([\d,.]+))/);
    const shippingCost = shippingMatch?.[1] === 'FREE' ? 0 : (shippingMatch?.[2] ? parseFloat(shippingMatch[2].replace(/,/g, '')) : 0);
    
    // Extract discount (look for "- ₹" pattern)
    const discountMatch = billText.match(/Coupon Discount[^-]*-\s*₹([\d,.]+)/);
    const discount = discountMatch ? parseFloat(discountMatch[1].replace(/,/g, '')) : 0;
    
    const finalTotal = subtotal + platformFee + shippingCost - discount;
    
    const itemCount = document.querySelectorAll('tbody tr').length - (document.querySelectorAll('.reason-row').length);
    document.getElementById('confirmOrderItemCount').textContent = itemCount + ' item' + (itemCount !== 1 ? 's' : '');
    document.getElementById('confirmOrderRefund').textContent = '₹' + number_format(finalTotal, 2);
    
    const paymentMethod = billText.includes('Cash on Delivery') ? 'cod' : 'prepaid';
    document.getElementById('orderRefundNote').textContent = paymentMethod === 'cod' ? 'No refund will be processed as this is a COD order.' : 'Refund of ₹' + number_format(finalTotal, 2) + ' will be processed within 5-7 business days.';
    
    document.getElementById('orderReasonSelectionStep').style.display = 'none';
    document.getElementById('orderConfirmationStep').style.display = 'block';
    document.getElementById('orderProceedBtn').style.display = 'none';
    document.getElementById('orderConfirmBtn').style.display = 'block';
}

function confirmOrderCancellation() {
    if (!selectedOrderReason) return;
    
    const customText = document.getElementById('orderCustomReasonText').value.trim();
    const orderId = window.location.pathname.split('/').pop();
    
    const button = document.getElementById('orderConfirmBtn');
    button.disabled = true;
    button.textContent = 'Processing...';
    
    fetch(`/order/${orderId}/cancel`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            cancel_reason: selectedOrderReason,
            custom_reason: customText
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showSuccessNotification('✓ Order cancelled successfully!');
            setTimeout(() => location.reload(), 1500);
        } else {
            alert('Error: ' + (data.message || 'Failed to cancel order'));
            button.disabled = false;
            button.textContent = 'Confirm Cancellation';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('An error occurred while cancelling the order.');
        button.disabled = false;
        button.textContent = 'Confirm Cancellation';
    });
}

function number_format(num, decimals = 2) {
    const factor = Math.pow(10, decimals);
    return (Math.round(num * factor) / factor).toFixed(decimals);
}

// Add custom reason input validation
const customReasonInput = document.getElementById('orderCustomReasonText');
if (customReasonInput) {
    customReasonInput.addEventListener('input', updateOrderProceedButton);
}

// Close modal when clicking outside
document.addEventListener('click', (e) => {
    const modal = document.getElementById('cancelOrderModal');
    if (modal && e.target === modal) {
        closeCancelOrderModal();
    }
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Veyronnnnnnnnnn\resources\views/shop/order-view.blade.php ENDPATH**/ ?>