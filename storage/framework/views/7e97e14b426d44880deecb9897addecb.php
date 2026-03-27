<?php $__env->startSection('title', 'Admin Dashboard — VEYRON'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: #3a3a3a;
    padding: 25px;
    border-radius: 12px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    text-align: center;
    transition: all 0.3s ease;
}

.stat-card:hover {
    background: #424242;
    transform: translateY(-3px);
}

.stat-card .icon {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.stat-card .value {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
}

.stat-card .label {
    color: #ffffff;
    font-size: 0.95rem;
    margin-top: 5px;
}

.stat-orders .icon { color: #3b82f6; }
.stat-sales .icon { color: #34d399; }
.stat-products .icon { color: #f59e0b; }
.stat-users .icon { color: #8b5cf6; }

.recent-orders {
    background: #3a3a3a;
    border-radius: 12px;
    padding: 25px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.recent-orders h3 {
    margin-bottom: 20px;
    font-size: 1.3rem;
    color: #fff;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
}

.orders-table th, .orders-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.orders-table th {
    font-weight: 600;
    color: #ffffff;
    font-size: 0.9rem;
    background: #2a2a2a;
}

.orders-table td {
    color: #ffffff;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-pending { background: #000000; color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.3); }
.status-processing { background: #000000; color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.3); }
.status-delivered { background: #000000; color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.3); }

@media (max-width: 1024px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<h2 style="margin-bottom: 25px; color: #fff;">Dashboard Overview</h2>

<div class="stats-grid">
    <div class="stat-card stat-orders">
        <div class="icon"><span class="material-icons" style="font-size:inherit;">shopping_cart</span></div>
        <div class="value"><?php echo e($totalOrders); ?></div>
        <div class="label">Total Orders</div>
    </div>
    <div class="stat-card stat-sales">
        <div class="icon"><span class="material-icons" style="font-size:inherit;">payments</span></div>
        <div class="value">₹<?php echo e(number_format($totalSales, 0)); ?></div>
        <div class="label">Total Sales</div>
    </div>
    <div class="stat-card stat-products">
        <div class="icon"><span class="material-icons" style="font-size:inherit;">inventory</span></div>
        <div class="value"><?php echo e($totalProducts); ?></div>
        <div class="label">Products</div>
    </div>
    <div class="stat-card stat-users">
        <div class="icon"><span class="material-icons" style="font-size:inherit;">people</span></div>
        <div class="value"><?php echo e($totalUsers); ?></div>
        <div class="label">Users</div>
    </div>
</div>

<div class="recent-orders">
    <h3>Recent Orders</h3>
    <?php if($recentOrders->count() > 0): ?>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>#<?php echo e(str_pad($order->id, 6, '0', STR_PAD_LEFT)); ?></td>
                        <td><?php echo e($order->name); ?></td>
                        <td>₹<?php echo e(number_format($order->total_amount, 2)); ?></td>
                        <td><span class="status-badge status-<?php echo e($order->order_status); ?>"><?php echo e(ucfirst($order->order_status)); ?></span></td>
                        <td><?php echo e($order->created_at->format('d M Y')); ?></td>
                        <td><a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" style="color:#34d399; text-decoration: none; font-weight: 600;">View</a></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="color:#888; text-align:center; padding:30px;">No orders yet.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Veyronnnnnnnnnn\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>