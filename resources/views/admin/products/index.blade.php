@extends('layouts.admin')

@section('title', 'Products — Admin')

@push('styles')
<style>
.products-container { display: grid; grid-template-columns: 6fr 4fr; gap: 25px; align-items: start; width: 100%; }

/* Products Section */
.products-section h2 { margin-bottom: 20px; color: #fff; }

.products-table { width: 100%; background: rgba(255, 255, 255, 0.08); border-radius: 12px; overflow: hidden; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.15); }
.products-table th, .products-table td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
.products-table th { background: rgba(52, 211, 153, 0.1); font-weight: 700; color: #cbd5e1; font-size: 0.9rem; }
.products-table tbody tr { transition: all 0.3s ease; }
.products-table tbody tr:hover { background: rgba(52, 211, 153, 0.1); }
.products-table img { width: 60px; height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(255, 255, 255, 0.1); }
.products-table td { color: #e2e8f0; }

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
    background: rgba(52, 211, 153, 0.2);
    color: #86efac;
}

.badge-warning {
    background: rgba(251, 191, 36, 0.2);
    color: #fcd34d;
}

.badge-danger {
    background: rgba(239, 68, 68, 0.2);
    color: #fca5a5;
}

.action-btn { padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; transition: all 0.3s ease; font-weight: 600; border: none; cursor: pointer; display: inline-block; white-space: nowrap; }
.btn-edit { background: linear-gradient(135deg, #34d399 0%, #10b981 100%); color: #fff; }
.btn-edit:hover { background: linear-gradient(135deg, #10b981 0%, #059669 100%); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
.btn-delete { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #fff; }
.btn-delete:hover { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }

/* Add Product Form Section */
.form-card { background: rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 25px; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.15); position: sticky; top: 100px; }
.form-card h3 { margin-bottom: 20px; color: #fff; font-size: 1.2rem; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.9rem; color: #cbd5e1; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 6px; font-size: 0.9rem; transition: all 0.3s ease; color: #e2e8f0; }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: rgba(52, 211, 153, 0.5); outline: none; box-shadow: 0 0 8px rgba(52, 211, 153, 0.2); background: rgba(255, 255, 255, 0.12); }
.form-group textarea { resize: vertical; }

.btn-submit { 
    background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
    color: #fff; 
    padding: 12px 20px; 
    border: none; 
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
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);
}

.categories-btn {
    background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
    color: #fff;
    padding: 10px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    display: inline-block;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.categories-btn:hover {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

@media (max-width: 1024px) {
    .products-container { grid-template-columns: 1fr; }
    .form-card { position: static; }
}
</style>
@endpush

@section('content')
<div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
    <a href="{{ route('admin.categories.index') }}" class="categories-btn">
        📂 Manage Categories
    </a>
    <a href="{{ route('admin.bulk.import.index') }}" class="categories-btn" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
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
                @forelse($products as $product)
                    <tr>
                        <td><img src="{{ asset($product->image) }}" alt="{{ $product->name }}"></td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->categoryModel ? $product->categoryModel->name : 'N/A' }}</td>
                        <td><strong>₹{{ number_format($product->price, 2) }}</strong></td>
                        <td>
                            <div class="stock-summary">
                                @foreach($product->getStockSummary() as $sizeData)
                                    <span class="badge 
                                        @if($sizeData['is_out']) badge-danger
                                        @elseif($sizeData['is_low']) badge-warning
                                        @else badge-success
                                        @endif">
                                        {{ $sizeData['size'] }}: {{ $sizeData['stock'] }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td><span style="background: rgba(52, 211, 153, 0.2); padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; color: #86efac;"><strong>{{ $product->getTotalStock() }}</strong></span></td>
                        <td>
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="action-btn btn-edit">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:40px; color:#999; font-size: 1rem;">No products found. Add your first product using the form.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add Product Form (4 parts) -->
    <div class="form-card">
        <h3>➕ Add New Product</h3>
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label>Product Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label>Price (₹) *</label>
                <input type="number" name="price" step="0.01" value="{{ old('price') }}" required>
            </div>

            <div class="form-group">
                <label>Category *</label>
                <select name="category_id" required>
                    <option value="">Select</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Available Sizes *</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                        <label style="display: flex; align-items: center; margin: 0;">
                            <input type="checkbox" name="sizes[]" value="{{ $size }}" style="width: auto; margin-right: 6px;" {{ in_array($size, ['S', 'M', 'L', 'XL']) ? 'checked' : '' }}>
                            <span>{{ $size }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label>Initial Stock (per size) *</label>
                <input type="number" name="stock" min="1" value="{{ old('stock', 5) }}" required>
                <small style="color: #94a3b8; display: block; margin-top: 4px;">This stock will be distributed equally to all selected sizes</small>
            </div>

            <div class="form-group">
                <label>Image *</label>
                <input type="file" name="image" accept="image/*" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn-submit">Add Product</button>
        </form>
    </div>
</div>
@endsection
