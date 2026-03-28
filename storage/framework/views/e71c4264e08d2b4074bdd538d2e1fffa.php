<?php $__env->startSection('title', 'Categories — Admin'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.categories-container {
    display: grid;
    grid-template-columns: 6fr 4fr;
    gap: 25px;
    align-items: start;
}

.categories-section h2 {
    margin-bottom: 20px;
    color: #fff;
}

.categories-table {
    width: 100%;
    background: #3a3a3a;
    border-radius: 12px;
    overflow: hidden;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.categories-table th,
.categories-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.categories-table th {
    background: #2a2a2a;
    font-weight: 700;
    color: #ffffff;
    font-size: 0.9rem;
}

.categories-table tbody tr:hover {
    background: #424242;
}

.categories-table td {
    color: #ffffff;
}

.categories-table code {
    background: #424242;
    padding: 2px 6px;
    border-radius: 3px;
    color: #ffffff;
}

.action-btn {
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.85rem;
    margin-right: 5px;
    font-weight: 600;
    border: 1px solid rgba(255, 255, 255, 0.3);
    cursor: pointer;
}

.btn-blue {
    background: #000000;
    color: #fff;
}

.btn-red {
    background: #000000;
    color: #fff;
}

.form-card {
    background: #3a3a3a;
    border-radius: 12px;
    padding: 25px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: sticky;
    top: 100px;
}

.form-card h3 {
    margin-bottom: 20px;
    color: #fff;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    font-size: 0.9rem;
    color: #ffffff;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    background: #424242;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    font-size: 0.9rem;
    color: #ffffff;
}

.btn-add {
    background: #000000;
    color: #fff;
    padding: 10px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 6px;
    font-weight: 600;
    width: 100%;
    cursor: pointer;
}

.alert-success {
    background: #1a1a1a;
    border: 1px solid rgba(168, 230, 168, 0.4);
    color: #a8e6a8;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 15px;
}

@media (max-width: 1024px) {
    .categories-container {
        grid-template-columns: 1fr;
    }
    .form-card {
        position: static;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php
function renderCategoryRow($category, $level = 0) {
    echo '<tr>';
    echo '<td><strong style="padding-left:' . ($level * 25) . 'px;">' . ($level ? '↳ ' : '') . e($category->name) . '</strong></td>';
    echo '<td><code>' . e($category->slug) . '</code></td>';
    echo '<td>
            <a href="' . route('admin.categories.edit', $category->id) . '" class="action-btn btn-blue">Edit</a>
            <form action="' . route('admin.categories.destroy', $category->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Delete this category and its subcategories?\')">
                ' . csrf_field() . method_field('DELETE') . '
                <button class="action-btn btn-red">Delete</button>
            </form>
          </td>';
    echo '</tr>';

    if ($category->children) {
        foreach ($category->children as $child) {
            renderCategoryRow($child, $level + 1);
        }
    }
}
?>

<div class="categories-container">

    <div class="categories-section">
        <h2>All Categories</h2>

        <?php if(session('success')): ?>
            <div class="alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <table class="categories-table">
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Slug</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php renderCategoryRow($category); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="3" style="text-align:center; padding:30px;">No categories found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="form-card">
        <h3>➕ Add New Category</h3>

        <form action="<?php echo e(route('admin.categories.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label>Category Name *</label>
                <input type="text" name="name" required placeholder="e.g. Shirts, Tops">
            </div>

            <div class="form-group">
                <label>Parent Category (Optional)</label>
                <select name="parent_id">
                    <option value="">— Main Category —</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($parent->id); ?>"><?php echo e($parent->name); ?></option>
                        <?php $__currentLoopData = $parent->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($child->id); ?>">↳ <?php echo e($child->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <button type="submit" class="btn-add">Add Category</button>
        </form>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Veyronnnnnnnnnn\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>