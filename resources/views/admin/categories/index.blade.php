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
