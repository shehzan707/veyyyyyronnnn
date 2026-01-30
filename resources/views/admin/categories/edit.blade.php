@extends('layouts.admin')

@section('title', 'Edit Category — Admin')

@push('styles')
<style>
.form-card {
    background: rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 30px;
    max-width: 520px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #cbd5e1;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 8px;
    font-size: 1rem;
    color: #e2e8f0;
}

.form-group input:focus,
.form-group select:focus {
    border-color: rgba(52, 211, 153, 0.5);
    outline: none;
    box-shadow: 0 0 8px rgba(52, 211, 153, 0.2);
    background: rgba(255, 255, 255, 0.12);
}

.form-row {
    display: flex;
    gap: 10px;
}

.btn-submit {
    background: linear-gradient(135deg, #34d399, #10b981);
    color: #fff;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
}

.btn-submit:hover {
    background: linear-gradient(135deg, #10b981, #059669);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-cancel {
    background: rgba(255, 255, 255, 0.08);
    color: #cbd5e1;
    padding: 12px 24px;
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    text-decoration: none;
}

.btn-cancel:hover {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255, 255, 255, 0.3);
}

h2 {
    color: #fff;
    margin-bottom: 25px;
}
</style>
@endpush

@section('content')

<h2>Edit Category</h2>

@php
function renderParentOptions($categories, $currentCategory, $level = 0) {
    foreach ($categories as $cat) {

        if ($cat->id === $currentCategory->id) {
            continue;
        }

        echo '<option value="'.$cat->id.'" '.($currentCategory->parent_id == $cat->id ? 'selected' : '').'>
                '.str_repeat('↳ ', $level).e($cat->name).'
              </option>';

        if ($cat->children) {
            renderParentOptions($cat->children, $currentCategory, $level + 1);
        }
    }
}
@endphp

<div class="form-card">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Category Name *</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
            @error('name')
                <span style="color:#fca5a5; font-size:0.85rem;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Parent Category (Optional)</label>
            <select name="parent_id">
                <option value="">— Main Category —</option>
                @php renderParentOptions($categories, $category); @endphp
            </select>
        </div>

        <div style="margin-top: 30px;">
            <div class="form-row">
                <button type="submit" class="btn-submit">Update Category</button>
                <a href="{{ route('admin.categories.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </div>
    </form>
</div>

@endsection
