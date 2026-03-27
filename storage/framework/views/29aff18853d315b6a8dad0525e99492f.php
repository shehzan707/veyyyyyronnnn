<?php $__env->startSection('title', 'Products — Admin'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.products-container { display: grid; grid-template-columns: 6fr 4fr; gap: 25px; align-items: start; width: 100%; }

/* Products Section */
.products-section { max-height: calc(100vh - 180px); overflow-y: auto; padding-right: 10px; }
.products-section h2 { margin-bottom: 20px; color: #fff; }

.products-table { width: 100%; background: #3a3a3a; border-radius: 12px; overflow: hidden; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
.products-table th, .products-table td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
.products-table th { background: #2a2a2a; font-weight: 700; color: #ffffff; font-size: 0.9rem; }
.products-table tbody tr { transition: all 0.3s ease; }
.products-table tbody tr:hover { background: #424242; }
.products-table img { width: 60px; height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(255, 255, 255, 0.1); }
.products-table td { color: #ffffff; }

.stock-summary {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-success {
    background: #000000;
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.badge-warning {
    background: #000000;
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.badge-danger {
    background: #000000;
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.action-btn { padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; transition: all 0.3s ease; font-weight: 600; border: 1px solid rgba(255, 255, 255, 0.3); cursor: pointer; display: inline-block; white-space: nowrap; }
.btn-edit { background: #000000; color: #fff; }
.btn-edit:hover { background: #1a1a1a; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5); }
.btn-delete { background: #000000; color: #fff; }
.btn-delete:hover { background: #1a1a1a; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5); }

/* Add Product Form Section */
.form-card { background: #3a3a3a; border-radius: 12px; padding: 25px; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); position: sticky; top: 100px; }
.form-card h3 { margin-bottom: 20px; color: #fff; font-size: 1.2rem; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.9rem; color: #ffffff; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; background: #424242; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 6px; font-size: 0.9rem; transition: all 0.3s ease; color: #ffffff; }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: rgba(255, 255, 255, 0.4); outline: none; box-shadow: 0 0 8px rgba(255, 255, 255, 0.15); background: #424242; }
.form-group textarea { resize: vertical; }

.btn-submit { 
    background: #000000;
    color: #fff; 
    padding: 12px 20px; 
    border: 1px solid rgba(255, 255, 255, 0.3); 
    border-radius: 6px; 
    font-size: 0.95rem; 
    font-weight: 700;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.btn-submit:hover { 
    background: #1a1a1a;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
}

.categories-btn {
    background: #000000;
    color: #fff;
    padding: 10px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    display: inline-block;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
    cursor: pointer;
}

.categories-btn:hover {
    background: #1a1a1a;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
}

@media (max-width: 1024px) {
    .products-container { grid-template-columns: 1fr; }
    .form-card { position: static; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
    <a href="<?php echo e(route('admin.categories.index')); ?>" class="categories-btn">
        📂 Manage Categories
    </a>
    <a href="<?php echo e(route('admin.bulk.import.index')); ?>" class="categories-btn" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
        📦 Bulk Import Products
    </a>
</div>

<div class="products-container">
    <!-- Products Section (6 parts) -->
    <div class="products-section">
        <h2>All Products</h2>
        <table class="products-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Size-Wise Stock</th>
                    <th>Total Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><img src="<?php echo e(asset($product->image)); ?>" alt="<?php echo e($product->name); ?>"></td>
                        <td><strong><?php echo e($product->name); ?></strong></td>
                        <td><?php echo e($product->categoryModel ? $product->categoryModel->name : 'N/A'); ?></td>
                        <td><strong>₹<?php echo e(number_format($product->price, 2)); ?></strong></td>
                        <td>
                            <div class="stock-summary">
                                <?php $__currentLoopData = $product->getStockSummary(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sizeData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge 
                                        <?php if($sizeData['is_out']): ?> badge-danger
                                        <?php elseif($sizeData['is_low']): ?> badge-warning
                                        <?php else: ?> badge-success
                                        <?php endif; ?>">
                                        <?php echo e($sizeData['size']); ?>: <?php echo e($sizeData['stock']); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>
                        <td><span style="background: #000000; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.3);"><strong><?php echo e($product->getTotalStock()); ?></strong></span></td>
                        <td>
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <a href="<?php echo e(route('admin.products.edit', $product->id)); ?>" class="action-btn btn-edit">Edit</a>
                                <form action="<?php echo e(route('admin.products.destroy', $product->id)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="action-btn btn-delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding:40px; color:#999; font-size: 1rem;">No products found. Add your first product using the form.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Product Form (4 parts) -->
    <div class="form-card">
        <h3>➕ Add New Product</h3>
        <form action="<?php echo e(route('admin.products.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <div class="form-group">
                <label>Product Name *</label>
                <input type="text" name="name" value="<?php echo e(old('name')); ?>" required>
            </div>

            <div class="form-group">
                <label>Price (₹) *</label>
                <input type="number" name="price" step="0.01" value="<?php echo e(old('price')); ?>" required>
            </div>

            <div class="form-group">
                <label>Category *</label>
                <select name="category_id" required>
                    <option value="">Select</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php echo e(old('category_id') == $cat->id ? 'selected' : ''); ?>>
                            <?php echo e($cat->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="form-group">
                <label>Available Sizes *</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <?php $__currentLoopData = ['XS', 'S', 'M', 'L', 'XL', 'XXL']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label style="display: flex; align-items: center; margin: 0;">
                            <input type="checkbox" name="sizes[]" value="<?php echo e($size); ?>" style="width: auto; margin-right: 6px;" <?php echo e(in_array($size, ['S', 'M', 'L', 'XL']) ? 'checked' : ''); ?>>
                            <span><?php echo e($size); ?></span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="form-group">
                <label>Initial Stock (per size) *</label>
                <input type="number" name="stock" min="1" value="<?php echo e(old('stock', 5)); ?>" required>
                <small style="color: #94a3b8; display: block; margin-top: 4px;">This stock will be distributed equally to all selected sizes</small>
            </div>

            <div class="form-group">
                <label>Image *</label>
                <input type="file" name="image" accept="image/*" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"><?php echo e(old('description')); ?></textarea>
            </div>

            <button type="submit" class="btn-submit">Add Product</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Veyronnnnnnnnnn\resources\views/admin/products/index.blade.php ENDPATH**/ ?>