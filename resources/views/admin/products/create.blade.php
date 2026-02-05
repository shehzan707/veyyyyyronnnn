@extends('layouts.admin')

@section('title', 'Add Product — Admin')

@push('styles')
<style>
.form-card { background: rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 30px; max-width: 700px; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.15); }
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
</style>
@endpush

@section('content')
<div style="margin-bottom: 30px;">
    <a href="{{ route('admin.products.index') }}" style="color: #86efac; text-decoration: none; font-weight: 600;">← Back to Products</a>
</div>

<div class="form-card">
    <h2>➕ Add New Product</h2>
    
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

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group @error('name') error @enderror">
            <label>Product Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
            @error('name')<span class="error-message">{{ $message }}</span>@enderror
        </div>

        <div class="form-row">
            <div class="form-group @error('price') error @enderror">
                <label>Price (₹) *</label>
                <input type="number" name="price" step="0.01" value="{{ old('price') }}" required>
                @error('price')<span class="error-message">{{ $message }}</span>@enderror
            </div>

            <div class="form-group @error('category_id') error @enderror">
                <label>Category *</label>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                <fieldset style="border: 1px solid rgba(255,255,255,0.15); padding: 12px; border-radius: 6px; margin-bottom: 12px;">
                    <legend style="color: #cbd5e1; padding: 0 8px; font-size: 0.9rem;">Apparel Sizes</legend>
                    @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                        <label>
                            <input type="checkbox" name="sizes[]" value="{{ $size }}" 
                                {{ in_array($size, old('sizes', ['S', 'M', 'L', 'XL'])) ? 'checked' : '' }}>
                            {{ $size }}
                        </label>
                    @endforeach
                </fieldset>
                <fieldset style="border: 1px solid rgba(255,255,255,0.15); padding: 12px; border-radius: 6px;">
                    <legend style="color: #cbd5e1; padding: 0 8px; font-size: 0.9rem;">Shoe Sizes</legend>
                    @foreach(['6', '7', '8', '9', '10'] as $size)
                        <label>
                            <input type="checkbox" name="sizes[]" value="{{ $size }}" 
                                {{ in_array($size, old('sizes', [])) ? 'checked' : '' }}>
                            {{ $size }}
                        </label>
                    @endforeach
                </fieldset>
            </div>
            @error('sizes')<span class="error-message">{{ $message }}</span>@enderror
        </div>

        <div class="form-group @error('stock') error @enderror">
            <label>Initial Stock (per size) *</label>
            <input type="number" name="stock" min="1" value="{{ old('stock', 5) }}" required>
            <small style="color: #94a3b8; display: block; margin-top: 6px;">This stock will be distributed equally to all selected sizes</small>
            @error('stock')<span class="error-message">{{ $message }}</span>@enderror
        </div>

        <div class="form-group @error('image') error @enderror">
            <label>Product Image *</label>
            <input type="file" name="image" accept="image/*" required>
            <small style="color: #94a3b8; display: block; margin-top: 6px;">Max 2MB. Supported: jpg, jpeg, png, webp</small>
            @error('image')<span class="error-message">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="5" style="resize: vertical;">{{ old('description', 'VEYRON is not merely a brand—it is a declaration of authority in modern luxury, a name that embodies precision, restraint, and uncompromising excellence. Conceived at the intersection of contemporary fashion and timeless sophistication, Veyron represents a world where every detail is intentional and every design decision is driven by purpose. The brand speaks to individuals who value subtle dominance over loud expression—those who understand that true luxury does not seek attention, it commands it. Veyron\'s identity is built on refinement, confidence, and an unwavering commitment to elevating everyday essentials into statements of distinction.

At the core of VeyRON lies an obsession with craftsmanship and material integrity. Each product is the result of meticulous design engineering, where premium fabrics, advanced textiles, and superior finishes converge to create pieces that are both visually commanding and functionally superior. From the weight of a garment to the precision of its stitching, nothing is left to chance. Veyron\'s approach emphasizes durability without sacrificing elegance—ensuring that every item maintains its structure, texture, and presence over time. This is luxury designed not just to be worn, but to endure.

VEYRON\'s design philosophy is defined by architectural minimalism and modern masculinity. Clean silhouettes, balanced proportions, and disciplined color palettes form the backbone of its aesthetic language. Rather than following transient trends, Veyron curates a timeless visual code—one that evolves intelligently while remaining rooted in its core values. Each collection is composed to deliver versatility, allowing pieces to transition seamlessly from formal environments to elevated casual settings. The result is a wardrobe that reflects confidence, control, and refined taste in every context.') }}</textarea>
            <small style="color: #94a3b8; display: block; margin-top: 6px;">Default VEYRON brand description is pre-filled. Edit if needed.</small>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-submit">Add Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>
@endsection
