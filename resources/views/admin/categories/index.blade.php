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

.bulk-actions {
    margin-bottom: 15px;
    display: flex;
    gap: 10px;
    align-items: center;
}

.bulk-actions button {
    padding: 10px 16px;
    border-radius: 6px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    font-size: 0.9rem;
}

.bulk-delete-btn {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    display: none;
}

.bulk-delete-btn:hover {
    opacity: 0.9;
}

.bulk-select-all {
    display: none;
}

.bulk-delete-btn.show,
.bulk-select-all.show {
    display: block;
}

.category-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #34d399;
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

/* Light Theme Overrides */
body.theme-light .categories-section h2 {
    color: #000000;
}

body.theme-light .categories-table {
    background: #ffffff;
    border: 1px solid #e0e0e0;
}

body.theme-light .categories-table th {
    background: #f9f9f9;
    color: #000000;
}

body.theme-light .categories-table tbody tr:hover {
    background: #f0f0f0;
}

body.theme-light .categories-table td {
    color: #000000;
    border-bottom: 1px solid #e0e0e0;
}

body.theme-light .categories-table code {
    background: #808080;
    color: #ffffff;
}

body.theme-light .btn-blue {
    background: #808080 !important;
    color: #ffffff !important;
}

body.theme-light .btn-blue:hover {
    background: #808080 !important;
    transform: none;
}

body.theme-light .btn-red {
    background: #808080 !important;
    color: #ffffff !important;
}

body.theme-light .btn-red:hover {
    background: #808080 !important;
    transform: none;
}

body.theme-light .form-card {
    background: #ffffff;
    border: 1px solid #e0e0e0;
}

body.theme-light .form-card h3 {
    color: #000000;
}

body.theme-light .form-group label {
    color: #333333;
}

body.theme-light .form-group input,
body.theme-light .form-group select {
    background: #ffffff;
    border: 1px solid #cccccc;
    color: #000000;
}

body.theme-light .btn-add {
    background: #808080 !important;
    color: #ffffff !important;
    border: none;
}

body.theme-light .btn-add:hover {
    background: #808080 !important;
    transform: none;
}

body.theme-light .category-checkbox {
    accent-color: #808080;
}

body.theme-light .bulk-delete-btn {
    background: #808080 !important;
    color: #ffffff !important;
}

body.theme-light .bulk-delete-btn:hover {
    background: #808080 !important;
}

body.theme-light .alert-success {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #22c55e;
}

/* Dark Theme Overrides */
body.theme-dark .categories-section h2 {
    color: #ffffff;
}

body.theme-dark .categories-table {
    background: #323232;
    border: 1px solid #444444;
}

body.theme-dark .categories-table th {
    background: #3d3d3d;
    color: #ffffff;
}

body.theme-dark .categories-table td {
    color: #ffffff;
    border-bottom: 1px solid #444444;
}

body.theme-dark .btn-blue {
    background: #808080 !important;
    color: #ffffff !important;
}

body.theme-dark .btn-blue:hover {
    background: #808080 !important;
    transform: none;
}

body.theme-dark .btn-red {
    background: #808080 !important;
    color: #ffffff !important;
}

body.theme-dark .btn-red:hover {
    background: #808080 !important;
    transform: none;
}

body.theme-dark .btn-add {
    background: #808080 !important;
    color: #ffffff !important;
}

body.theme-dark .btn-add:hover {
    background: #808080 !important;
    transform: none;
}
</style>
@endpush

@section('content')

@php
function renderCategoryRow($category, $level = 0) {
    echo '<tr data-category-id="' . $category->id . '">';
    echo '<td><input type="checkbox" class="category-checkbox bulk-checkbox" data-category-id="' . $category->id . '" onchange="updateBulkActions()"></td>';
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

        <div class="bulk-actions">
            <input type="checkbox" id="selectAll" class="bulk-select-all" onchange="toggleSelectAll(this)">
            <label for="selectAll" style="margin: 0; color: #cbd5e1; cursor: pointer;">Select All</label>
            <form action="{{ route('admin.categories.bulk-delete') }}" method="POST" id="bulkDeleteForm" style="display:inline;">
                @csrf
                <input type="hidden" name="category_ids" id="categoryIds" value="">
                <button type="submit" class="bulk-delete-btn" onclick="return confirm('Delete selected categories and their subcategories? This cannot be undone.')">
                    Delete Selected
                </button>
            </form>
        </div>

        <table class="categories-table">
            <thead>
                <tr>
                    <th style="width: 40px;"></th>
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
                        <td colspan="4" style="text-align:center; padding:30px;">No categories found.</td>
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

<script>
function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.bulk-checkbox');
    const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
    const deleteBtn = document.querySelector('.bulk-delete-btn');
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectAllLabel = document.querySelector('.bulk-select-all');

    if (selectedCount > 0) {
        deleteBtn.classList.add('show');
        selectAllCheckbox.classList.add('show');
        selectAllLabel.classList.add('show');
        
        // Update hidden input with selected IDs
        const selectedIds = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.dataset.categoryId);
        document.getElementById('categoryIds').value = JSON.stringify(selectedIds);
    } else {
        deleteBtn.classList.remove('show');
        selectAllCheckbox.classList.remove('show');
        selectAllLabel.classList.remove('show');
        selectAllCheckbox.checked = false;
    }
}

function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.bulk-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateBulkActions();
}
</script>
@endsection
