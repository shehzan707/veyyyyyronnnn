@extends('layouts.admin')

@section('title', 'Categories — Admin')

@push('styles')
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
    background: rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    overflow: hidden;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.categories-table th,
.categories-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.categories-table th {
    background: rgba(52, 211, 153, 0.1);
    font-weight: 700;
    color: #cbd5e1;
    font-size: 0.9rem;
}

.categories-table tbody tr:hover {
    background: rgba(52, 211, 153, 0.1);
}

.categories-table td {
    color: #e2e8f0;
}

.categories-table code {
    background: rgba(52, 211, 153, 0.15);
    padding: 2px 6px;
    border-radius: 3px;
    color: #86efac;
}

.action-btn {
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.85rem;
    margin-right: 5px;
    font-weight: 600;
    border: none;
    cursor: pointer;
}

.btn-blue {
    background: linear-gradient(135deg, #34d399, #10b981);
    color: #fff;
}

.btn-red {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
}

.form-card {
    background: rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 25px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.15);
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
    color: #cbd5e1;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 6px;
    font-size: 0.9rem;
    color: #e2e8f0;
}

.btn-add {
    background: linear-gradient(135deg, #34d399, #10b981);
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    width: 100%;
    cursor: pointer;
}

.alert-success {
    background: rgba(52, 211, 153, 0.2);
    border: 1px solid rgba(52, 211, 153, 0.4);
    color: #86efac;
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
@endpush

@section('content')

@php
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
@endphp

<div class="categories-container">

    <div class="categories-section">
        <h2>All Categories</h2>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <table class="categories-table">
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Slug</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    @php renderCategoryRow($category); @endphp
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:30px;">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="form-card">
        <h3>➕ Add New Category</h3>

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Category Name *</label>
                <input type="text" name="name" required placeholder="e.g. Shirts, Tops">
            </div>

            <div class="form-group">
                <label>Parent Category (Optional)</label>
                <select name="parent_id">
                    <option value="">— Main Category —</option>
                    @foreach($categories as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @foreach($parent->children as $child)
                            <option value="{{ $child->id }}">↳ {{ $child->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-add">Add Category</button>
        </form>
    </div>

</div>
@endsection
