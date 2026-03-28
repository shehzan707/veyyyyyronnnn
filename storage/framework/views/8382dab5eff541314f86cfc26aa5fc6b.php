

<?php $__env->startSection('title', 'Edit Banner — Admin'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.edit-container {
    max-width: 600px;
    margin: 40px auto;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.edit-title {
    color: #fff;
    font-size: 1.8rem;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-group {
    margin-bottom: 24px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    color: #cbd5e1;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 6px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.03);
    color: #e2e8f0;
    font-family: inherit;
}

.form-group input:focus,
.form-group select:focus {
    border-color: rgba(0, 212, 255, 0.6);
    outline: none;
    box-shadow: 0 0 8px rgba(0, 212, 255, 0.2);
    background: rgba(255, 255, 255, 0.05);
}

.banner-preview {
    margin-bottom: 30px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid rgba(0, 212, 255, 0.2);
    background: rgba(0, 0, 0, 0.3);
}

.preview-label {
    display: block;
    color: #94a3b8;
    font-size: 0.85rem;
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.preview-media {
    width: 100%;
    height: 250px;
    object-fit: cover;
    display: block;
}

.banner-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 30px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
}

.meta-item {
    text-align: center;
}

.meta-label {
    font-size: 0.8rem;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}

.meta-value {
    color: #fff;
    font-weight: 600;
}

.button-group {
    display: flex;
    gap: 12px;
}

.btn {
    flex: 1;
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-primary {
    background: #000000;
    color: #fff;
}

.btn-primary:hover {
    background: #333333;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.3);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.08);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255, 255, 255, 0.3);
}

@media (max-width: 768px) {
    .edit-container {
        margin: 20px;
        padding: 25px;
    }

    .button-group {
        flex-direction: column;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="edit-container">
    <div class="edit-title">
        <span class="material-icons">edit</span>
        Edit Banner
    </div>

    <!-- Banner Preview -->
    <div class="banner-preview">
        <span class="preview-label">Current Media</span>
        <?php if($banner->media_type === 'image'): ?>
            <img src="<?php echo e(asset($banner->file_path)); ?>" alt="<?php echo e($banner->file_name); ?>" class="preview-media">
        <?php else: ?>
            <video class="preview-media" controls>
                <source src="<?php echo e(asset($banner->file_path)); ?>" type="video/mp4">
            </video>
        <?php endif; ?>
    </div>

    <!-- Banner Metadata -->
    <div class="banner-meta">
        <div class="meta-item">
            <div class="meta-label">Type</div>
            <div class="meta-value"><?php echo e(strtoupper($banner->media_type)); ?></div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Uploaded</div>
            <div class="meta-value"><?php echo e($banner->created_at->format('M d, Y')); ?></div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Current Section</div>
            <div class="meta-value"><?php echo e($banner->section ?? 'default'); ?></div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Enabled</div>
            <div class="meta-value"><?php echo e($banner->is_enabled ? '✓ Yes' : '✗ No'); ?></div>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="<?php echo e(route('admin.banners.update', $banner->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="form-group">
            <label>Section *</label>
            <select name="section" required>
                <option value="default" <?php echo e($banner->section === 'default' ? 'selected' : ''); ?>>Default Home</option>
                <option value="men" <?php echo e($banner->section === 'men' ? 'selected' : ''); ?>>Men</option>
                <option value="women" <?php echo e($banner->section === 'women' ? 'selected' : ''); ?>>Women</option>
                <option value="accessories" <?php echo e($banner->section === 'accessories' ? 'selected' : ''); ?>>Accessories</option>
                <option value="footwear" <?php echo e($banner->section === 'footwear' ? 'selected' : ''); ?>>Footwear</option>
            </select>
        </div>

        <div class="form-group">
            <label>Banner Link (Product/Category URL)</label>
            <input 
                type="url" 
                name="banner_link" 
                value="<?php echo e($banner->banner_link); ?>" 
                placeholder="https://example.com/products"
                title="Full URL to product or category page"
            >
        </div>

        <div class="form-group">
            <label>Display Order</label>
            <input 
                type="number" 
                name="display_order" 
                value="<?php echo e($banner->display_order); ?>" 
                min="0" 
                step="1"
                title="Lower numbers appear first"
            >
        </div>

        <div class="button-group">
            <a href="<?php echo e(route('admin.banners.index')); ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Banner</button>
        </div>
    </form>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Veyronnnnnnnnnn\resources\views/admin/banners/edit.blade.php ENDPATH**/ ?>