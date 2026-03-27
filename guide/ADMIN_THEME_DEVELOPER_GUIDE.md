# Admin Theme - Developer Quick Reference

## CSS Classes & Usage

### Button Classes

```html
<!-- Green Action Buttons -->
<button class="btn-submit">Add Item</button>
<a href="#" class="action-btn btn-edit">Edit</a>

<!-- Red Delete Buttons -->
<button class="action-btn btn-delete" onclick="return confirm('Delete?')">Delete</button>
```

### Form Classes

```html
<!-- Form Card Container -->
<div class="form-card">
    <h3>Add New Product</h3>
    
    <!-- Form Group -->
    <div class="form-group">
        <label>Product Name *</label>
        <input type="text" name="name" required>
    </div>
    
    <!-- Submit Button -->
    <button type="submit" class="btn-submit">Add Product</button>
</div>
```

### Table Classes

```html
<!-- Table -->
<table class="products-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Product Name</td>
            <td>₹999</td>
            <td>
                <a href="#" class="action-btn btn-edit">Edit</a>
                <button class="action-btn btn-delete">Delete</button>
            </td>
        </tr>
    </tbody>
</table>
```

### Card Classes

```html
<!-- Info Card -->
<div class="kpi-card">
    <h4>Total Revenue</h4>
    <p>₹125,000</p>
</div>

<!-- Alert Message -->
<div class="alert alert-success">Operation successful!</div>
<div class="alert alert-error">Something went wrong!</div>
```

## Color Variables (For Reference)

```css
/* Backgrounds */
--bg-gradient-start: #0f2027;
--bg-gradient-mid: #2c5364;
--bg-card: rgba(255, 255, 255, 0.08);
--bg-card-hover: rgba(255, 255, 255, 0.12);

/* Text Colors */
--text-primary: #e2e8f0;
--text-secondary: #cbd5e1;
--text-light: #94a3b8;

/* Button Colors */
--btn-success: linear-gradient(135deg, #34d399 0%, #10b981 100%);
--btn-success-hover: linear-gradient(135deg, #10b981 0%, #059669 100%);
--btn-danger: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
--btn-danger-hover: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);

/* Borders & Shadows */
--border-color: rgba(255, 255, 255, 0.15);
--shadow-card: 0 4px 15px rgba(0,0,0,0.3);
--shadow-btn: 0 4px 12px rgba(16, 185, 129, 0.3);
```

## Common Component Patterns

### Add New Item Form (Sidebar)

```blade
<div class="form-card">
    <h3>➕ Add New Category</h3>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label>Category Name *</label>
            <input type="text" name="name" required>
        </div>

        <button type="submit" class="btn-add">Add Category</button>
    </form>
</div>
```

### Data Table with Actions

```blade
<table class="products-table">
    <thead>
        <tr>
            <th>Column 1</th>
            <th>Column 2</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->value }}</td>
                <td>
                    <a href="{{ route('edit', $item->id) }}" class="action-btn btn-edit">Edit</a>
                    <form action="{{ route('destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" style="text-align:center; padding:30px; color:#cbd5e1;">
                    No items found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
```

### Status Badge

```html
<span class="status-badge status-pending">Pending</span>
<span class="status-badge status-processing">Processing</span>
<span class="status-badge status-delivered">Delivered</span>
<span class="status-badge status-cancelled">Cancelled</span>
```

### KPI Card (Dashboard)

```html
<div class="stat-card stat-sales">
    <div class="icon">
        <span class="material-icons">payments</span>
    </div>
    <div class="value">₹125,000</div>
    <div class="label">Total Sales</div>
</div>
```

## Form Input Examples

```blade
<!-- Text Input -->
<input type="text" placeholder="Enter text" class="form-control">

<!-- Select Dropdown -->
<select name="category_id" required>
    <option value="">Select Category</option>
    @foreach($categories as $cat)
        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
    @endforeach
</select>

<!-- Textarea -->
<textarea rows="4" placeholder="Enter description"></textarea>

<!-- File Upload -->
<input type="file" accept="image/*" required>

<!-- Number Input -->
<input type="number" step="0.01" placeholder="0.00">

<!-- Checkbox -->
<input type="checkbox" name="active">

<!-- Radio Button -->
<input type="radio" name="status" value="active">
```

## Navigation Structure

```blade
@extends('layouts.admin')

@section('title', 'Page Title — Admin')

@push('styles')
<style>
    /* Custom page styles here */
</style>
@endpush

@section('content')
    <h2>Page Heading</h2>
    
    <!-- Page content -->
    
@endsection
```

## Adding a New Admin Page

1. **Create the view file** in `resources/views/admin/[section]/[page].blade.php`

2. **Extend admin layout:**
```blade
@extends('layouts.admin')
@section('title', 'Page Title — Admin')
```

3. **Add custom styles** in `@push('styles')` block

4. **Use theme classes** for buttons and forms:
   - `btn-submit` for green action buttons
   - `btn-delete` for red delete buttons
   - `form-card` for form containers
   - `products-table` / `categories-table` for tables

5. **Follow color scheme:**
   - Text: `#e2e8f0` (headings), `#cbd5e1` (labels)
   - Backgrounds: `rgba(255,255,255,0.08)` with `backdrop-filter: blur(10px)`
   - Buttons: Green for actions, Red for delete only

## Debugging Tips

### If colors don't match:
- Check that `admin.blade.php` layout is extended correctly
- Verify `!important` flags are applied for override-critical styles
- Check for conflicting inline styles

### If buttons aren't styled:
- Use exact class names: `.btn-submit`, `.action-btn.btn-delete`
- Ensure `<button>` elements are used (not `<input>` buttons)
- Apply classes to correct elements

### If table looks light:
- Add `class="products-table"` to the `<table>` element
- Verify `<thead>` and `<tbody>` are properly structured
- Check for inline styles overriding theme styles

### If form is misaligned:
- Wrap in `<div class="form-card">`
- Use `<div class="form-group">` for each input
- Ensure proper nesting of elements

## Performance Notes

- ✅ CSS Grid and Flexbox for layouts
- ✅ GPU-accelerated transforms (translateY)
- ✅ Backdrop blur is hardware accelerated
- ✅ Smooth 300ms transitions
- ✅ Minimal JavaScript required

## Accessibility

- ✅ Proper heading hierarchy (h1 → h6)
- ✅ Form labels connected to inputs
- ✅ High contrast text colors
- ✅ Focus states visible on all inputs
- ✅ Semantic HTML structure
- ✅ ARIA labels where needed

## Browser Support

| Feature | Chrome | Firefox | Safari | Edge |
|---------|--------|---------|--------|------|
| CSS Grid | ✅ | ✅ | ✅ | ✅ |
| Flexbox | ✅ | ✅ | ✅ | ✅ |
| Backdrop Filter | ✅ | ✅ | ✅ | ✅ |
| CSS Gradients | ✅ | ✅ | ✅ | ✅ |
| CSS Transforms | ✅ | ✅ | ✅ | ✅ |

## Quick Copy-Paste Templates

### Minimal Page Template
```blade
@extends('layouts.admin')

@section('title', 'New Page — Admin')

@push('styles')
<style>
    /* Custom styles */
</style>
@endpush

@section('content')
    <h2>Page Title</h2>
    <!-- Content here -->
@endsection
```

### Form Template
```blade
<div class="form-card">
    <h3>Form Title</h3>
    <form action="{{ route('store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Field Label *</label>
            <input type="text" name="field" required>
        </div>
        <button type="submit" class="btn-submit">Submit</button>
    </form>
</div>
```

### Table Template
```blade
<table class="products-table">
    <thead>
        <tr>
            <th>Header 1</th>
            <th>Header 2</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $item)
            <tr>
                <td>{{ $item->field1 }}</td>
                <td>{{ $item->field2 }}</td>
                <td>
                    <a href="#" class="action-btn btn-edit">Edit</a>
                    <button class="action-btn btn-delete">Delete</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" style="text-align:center; color:#cbd5e1;">No items</td>
            </tr>
        @endforelse
    </tbody>
</table>
```

---

**Version**: 1.0  
**Last Updated**: 2024  
**Maintained By**: Admin Panel Development Team
