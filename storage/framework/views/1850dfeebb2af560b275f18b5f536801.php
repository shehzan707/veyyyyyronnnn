<?php $__env->startSection('title', 'Order Confirmed — VEYRON'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.success-container {
    max-width: 700px;
    margin: 50px auto;
    text-align: center;
    background: #fff;
    padding: 50px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.success-icon {
    width: 80px;
    height: 80px;
    background: #16a34a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 40px;
    color: #fff;
}

.order-details {
    text-align: left;
    margin: 30px 0;
    padding: 20px;
    background: #f9fafb;
    border-radius: 12px;
}

.order-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #e5e7eb;
}

.order-row:last-child { border-bottom: none; }

.btn-continue {
    display: inline-block;
    padding: 15px 40px;
    background: #222;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    margin-top: 20px;
}

.btn-continue:hover { background: #444; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="success-container">
    <div class="success-icon">✓</div>
    <h1>Order Confirmed!</h1>
    <p style="color: #666; margin: 15px 0;">Thank you for your purchase. Your order has been placed successfully.</p>

    <div class="order-details">
        <div class="order-row">
            <span>Order ID</span>
            <strong>#<?php echo e(str_pad($order->id, 6, '0', STR_PAD_LEFT)); ?></strong>
        </div>
        <div class="order-row">
            <span>Name</span>
            <span><?php echo e($order->name); ?></span>
        </div>
        <div class="order-row">
            <span>Email</span>
            <span><?php echo e($order->email); ?></span>
        </div>
        <div class="order-row">
            <span>Delivery Address</span>
            <span><?php echo e($order->address); ?>, <?php echo e($order->city); ?>, <?php echo e($order->state); ?> - <?php echo e($order->pincode); ?></span>
        </div>
        <div class="order-row">
            <span>Payment Method</span>
            <span><?php echo e($order->payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment'); ?></span>
        </div>
        <div class="order-row">
            <span>Total Amount</span>
            <strong style="font-size: 1.2rem;">₹<?php echo e(number_format($order->total_amount, 2)); ?></strong>
        </div>
    </div>

    <h3 style="margin-bottom: 15px;">Order Items</h3>
    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="display:flex; justify-content:space-between; padding:10px; background:#f5f5f5; border-radius:8px; margin-bottom:10px;">
            <span><?php echo e($item->product_name); ?> x <?php echo e($item->quantity); ?></span>
            <span>₹<?php echo e(number_format($item->price * $item->quantity, 2)); ?></span>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <a href="<?php echo e(route('products.index')); ?>" class="btn-continue">Continue Shopping</a>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Veyronnnnnnnnnn\resources\views/shop/order-success.blade.php ENDPATH**/ ?>