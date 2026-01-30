@extends('layouts.admin')

@section('title', 'Edit Product — Admin')

@push('styles')
<style>
.form-card { background: rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 30px; max-width: 900px; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.15); }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #cbd5e1; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 8px; font-size: 1rem; color: #e2e8f0; }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: rgba(52, 211, 153, 0.5); outline: none; background: rgba(255, 255, 255, 0.12); box-shadow: 0 0 8px rgba(52, 211, 153, 0.2); }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.btn-submit { background: linear-gradient(135deg, #34d399 0%, #10b981 100%); color: #fff; padding: 14px 30px; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; }
.btn-submit:hover { background: linear-gradient(135deg, #10b981 0%, #059669 100%); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3); }
.btn-cancel { background: rgba(255, 255, 255, 0.08); color: #cbd5e1; padding: 14px 30px; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 8px; font-size: 1rem; cursor: pointer; margin-left: 10px; text-decoration: none; transition: all 0.3s ease; }
.btn-cancel:hover { background: rgba(255, 255, 255, 0.12); border-color: rgba(255, 255, 255, 0.3); }
h2 { color: #fff; }

.size-selection {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-top: 8px;
}

.size-selection label {
    display: flex;
    align-items: center;
    margin: 0;
}

.size-selection input[type="checkbox"] {
    width: auto;
    margin-right: 8px;
    cursor: pointer;
}

.error-message { color: #fca5a5; font-size: 0.85rem; margin-top: 4px; }
.form-group.error input, .form-group.error select { border-color: #fca5a5; background: rgba(239, 68, 68, 0.1); }

.inventory-section { background: rgba(52, 211, 153, 0.1); border: 1px solid rgba(52, 211, 153, 0.3); border-radius: 8px; padding: 20px; margin: 25px 0; }
.inventory-section h3 { color: #86efac; margin-top: 0; margin-bottom: 15px; }
.inventory-table { width: 100%; border-collapse: collapse; }
.inventory-table th { background: rgba(52, 211, 153, 0.2); padding: 12px; text-align: left; color: #cbd5e1; font-weight: 600; border-bottom: 2px solid rgba(52, 211, 153, 0.3); }
.inventory-table td { padding: 12px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); color: #e2e8f0; }
.inventory-table tbody tr:hover { background: rgba(52, 211, 153, 0.1); }

.stock-input { width: 80px !important; padding: 8px !important; }
.btn-action { padding: 8px 12px; border-radius: 6px; font-size: 0.85rem; border: none; cursor: pointer; transition: all 0.3s ease; font-weight: 600; }
.btn-update-stock { background: rgba(52, 211, 153, 0.3); color: #86efac; }
.btn-update-stock:hover { background: rgba(52, 211, 153, 0.5); }
.btn-toggle { background: rgba(251, 191, 36, 0.3); color: #fcd34d; }
.btn-toggle:hover { background: rgba(251, 191, 36, 0.5); }

.badge { padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; display: inline-block; }
.badge-success { background: rgba(52, 211, 153, 0.2); color: #86efac; }
.badge-warning { background: rgba(251, 191, 36, 0.2); color: #fcd34d; }
.badge-danger { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }
.badge-secondary { background: rgba(100, 116, 139, 0.2); color: #cbd5e1; }

.product-image-preview { max-width: 200px; height: auto; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.1); margin: 10px 0; }
</style>
@endpush

@section('content')
<div style="margin-bottom: 30px;">
    <a href="{{ route('admin.products.index') }}" style="color: #86efac; text-decoration: none; font-weight: 600;">← Back to Products</a>
</div>

<div class="form-card">
    <h2>✏️ Edit Product</h2>
    
    @if($errors->any())
        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; padding: 15px; margin-bottom: 20px; color: #fca5a5;">
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 10px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="form-group @error('name') error @enderror">
            <label>Product Name *</label>
            <input type="text" name="name" value="{{ $product->name }}" required>
            @error('name')<span class="error-message">{{ $message }}</span>@enderror
        </div>

        <div class="form-row">
            <div class="form-group @error('price') error @enderror">
                <label>Price (₹) *</label>
                <input type="number" name="price" step="0.01" value="{{ $product->price }}" required>
                @error('price')<span class="error-message">{{ $message }}</span>@enderror
            </div>

            <div class="form-group @error('category_id') error @enderror">
                <label>Category *</label>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<span class="error-message">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-group @error('sizes') error @enderror">
            <label>Available Sizes *</label>
            <div class="size-selection">
                @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                    <label>
                        <input type="checkbox" name="sizes[]" value="{{ $size }}" 
                            {{ in_array($size, (is_array($product->sizes) ? $product->sizes : json_decode($product->sizes, true) ?? [])) ? 'checked' : '' }}>
                        {{ $size }}
                    </label>
                @endforeach
            </div>
            @error('sizes')<span class="error-message">{{ $message }}</span>@enderror
        </div>

        <div class="form-group @error('stock') error @enderror">
            <label>Base Stock *</label>
            <input type="number" name="stock" value="{{ $product->stock }}" required>
            @error('stock')<span class="error-message">{{ $message }}</span>@enderror
        </div>

        <div class="form-group @error('image') error @enderror">
            <label>Product Image</label>
            @if($product->image)
                <div>
                    <img src="{{ asset($product->image) }}" alt="Product" class="product-image-preview">
                </div>
            @endif
            <input type="file" name="image" accept="image/*">
            <small style="color: #94a3b8; display: block; margin-top: 6px;">Leave empty to keep current image. Max 2MB. Supported: jpg, jpeg, png, webp</small>
            @error('image')<span class="error-message">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="5" style="resize: vertical;">{{ $product->description }}</textarea>
        </div>

        <!-- Size-Wise Inventory Management -->
        <div class="inventory-section">
            <h3>📦 Size-Wise Inventory Management</h3>
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>Size</th>
                        <th>Current Stock</th>
                        <th>Status</th>
                        <th>New Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($product->sizeVariants as $size)
                        <tr>
                            <td><strong>{{ $size->size }}</strong></td>
                            <td>
                                <span class="badge @if($size->stock == 0) badge-danger
                                    @elseif($size->stock <= 2) badge-warning
                                    @else badge-success
                                    @endif">
                                    {{ $size->stock }}
                                </span>
                            </td>
                            <td>
                                @if(!$size->is_available)
                                    <span class="badge badge-secondary">Unavailable</span>
                                @elseif($size->stock == 0)
                                    <span class="badge badge-danger">Out of Stock</span>
                                @elseif($size->stock <= 2)
                                    <span class="badge badge-warning">Low Stock</span>
                                @else
                                    <span class="badge badge-success">In Stock</span>
                                @endif
                            </td>
                            <td>
                                <input type="number" class="stock-input" data-size="{{ $size->size }}" data-size-id="{{ $size->id }}" value="{{ $size->stock }}" min="0">
                            </td>
                            <td>
                                <button type="button" class="btn-action btn-update-stock" data-size-id="{{ $size->id }}" data-product-id="{{ $product->id }}">
                                    Update
                                </button>
                                <button type="button" class="btn-action btn-toggle" data-size-id="{{ $size->id }}" data-product-id="{{ $product->id }}">
                                    {{ $size->is_available ? 'Disable' : 'Enable' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #999;">No sizes added</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-submit">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('.btn-update-stock').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        
        const sizeId = this.dataset.sizeId;
        const productId = this.dataset.productId;
        
        // Find the input field in the same row
        const row = this.closest('tr');
        const input = row.querySelector('.stock-input');
        
        if (!input) {
            alert('Error: Could not find stock input field');
            return;
        }
        
        const sizeName = input.dataset.size;
        const newStock = parseInt(input.value);
        
        console.log('Updating:', { sizeName, newStock, productId });
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            alert('Security token not found. Please refresh the page.');
            return;
        }

        if (isNaN(newStock)) {
            alert('Please enter a valid stock number');
            return;
        }

        fetch(`/admin/products/${productId}/update-stock`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                size: sizeName,
                stock: newStock
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert(`✓ Size ${sizeName} stock updated to ${newStock}!`);
                setTimeout(() => location.reload(), 500);
            } else {
                alert('❌ Failed to update stock: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error updating stock: ' + error.message);
        });
    });
});

document.querySelectorAll('.btn-toggle').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        
        const productId = this.dataset.productId;
        const sizeId = this.dataset.sizeId;
        
        // Find the input field in the same row
        const row = this.closest('tr');
        const input = row.querySelector('.stock-input');
        
        if (!input) {
            alert('Error: Could not find size input field');
            return;
        }
        
        const sizeName = input.dataset.size;
        
        console.log('Toggling availability:', { sizeName, productId });
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            alert('Security token not found. Please refresh the page.');
            return;
        }

        fetch(`/admin/products/${productId}/toggle-availability`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                size: sizeName
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert(`✓ Size ${sizeName} availability toggled!`);
                setTimeout(() => location.reload(), 500);
            } else {
                alert('❌ Failed to toggle availability: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error toggling availability: ' + error.message);
        });
    });
});
</script>
@endsection
