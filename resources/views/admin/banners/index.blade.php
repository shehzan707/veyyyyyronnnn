@extends('layouts.admin')

@section('title', 'Banners — Admin')

@push('styles')
<style>
.banners-container { display: grid; grid-template-columns: 7fr 3.5fr; gap: 25px; align-items: start; width: 100%; }

/* Banners Section */
.banners-section h2 { margin-bottom: 20px; color: #fff; }

.section-filters { 
    display: flex; 
    gap: 10px; 
    margin-bottom: 20px; 
    flex-wrap: wrap;
}

.filter-btn {
    padding: 8px 16px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #cbd5e1;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.85rem;
    font-weight: 600;
}

.filter-btn:hover,
.filter-btn.active {
    background: rgba(0, 212, 255, 0.2);
    border-color: rgba(0, 212, 255, 0.6);
    color: #fff;
}

.banners-grid { display: grid; gap: 20px; }

.banner-card { 
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    display: flex;
    gap: 15px;
    padding: 15px;
    position: relative;
}

.banner-card:hover {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(0, 212, 255, 0.3);
    transform: translateX(5px);
    box-shadow: 0 8px 20px rgba(0, 212, 255, 0.1);
}

.banner-thumbnail {
    width: 120px;
    height: 90px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
    border: 2px solid rgba(0, 212, 255, 0.2);
}

.banner-info { flex: 1; display: flex; flex-direction: column; justify-content: space-between; }

.banner-type {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: fit-content;
    margin-bottom: 5px;
}

.banner-type.image { background: rgba(59, 130, 246, 0.2); color: #93c5fd; }
.banner-type.video { background: rgba(34, 197, 94, 0.2); color: #86efac; }

.banner-section-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: fit-content;
    background: rgba(168, 85, 247, 0.2);
    color: #d8b4fe;
    margin-left: 8px;
}

.banner-name { color: #fff; font-weight: 600; margin-bottom: 3px; }

.banner-details {
    display: flex;
    gap: 12px;
    align-items: center;
    font-size: 0.8rem;
    color: #94a3b8;
    margin: 8px 0;
}

.banner-link-preview {
    color: #60a5fa;
    text-decoration: none;
    font-size: 0.8rem;
    word-break: break-all;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 24px;
    margin: 0 8px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #4b5563;
    transition: 0.3s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #22c55e;
}

input:checked + .toggle-slider:before {
    transform: translateX(16px);
}

.banner-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-icon {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    color: #fff;
}

.btn-delete {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.btn-delete:hover {
    background: rgba(239, 68, 68, 0.25);
    border-color: rgba(239, 68, 68, 0.6);
}

.btn-icon .material-icons { font-size: 1.1rem; }

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #cbd5e1;
    border: 2px dashed rgba(255, 255, 255, 0.15);
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.03);
}

.empty-state .material-icons { font-size: 3rem; opacity: 0.5; margin-bottom: 10px; }
.empty-state p { margin: 0; font-size: 1rem; }

/* Upload Form */
.form-card { 
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 12px; 
    padding: 25px; 
    box-shadow: 0 4px 15px rgba(0,0,0,0.08); 
    position: sticky; 
    top: 100px;
}

.form-card h3 { margin-bottom: 20px; color: #fff; font-size: 1.2rem; }

.form-group { margin-bottom: 16px; }

.form-group label { 
    display: block; 
    margin-bottom: 8px; 
    font-weight: 600; 
    font-size: 0.9rem; 
    color: #cbd5e1;
}

.form-group input, .form-group select, .form-group textarea { 
    width: 100%; 
    padding: 10px 12px; 
    border: 1px solid rgba(255, 255, 255, 0.15); 
    border-radius: 6px; 
    font-size: 0.9rem; 
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.03);
    color: #e2e8f0;
    font-family: inherit;
}

.form-group input:focus, .form-group select:focus, .form-group textarea:focus { 
    border-color: rgba(0, 212, 255, 0.6); 
    outline: none; 
    box-shadow: 0 0 8px rgba(0, 212, 255, 0.2);
    background: rgba(255, 255, 255, 0.05);
}

.form-group textarea {
    resize: vertical;
    min-height: 60px;
}

.file-input-wrapper {
    position: relative;
    cursor: pointer;
    display: block;
}

.file-input-label {
    display: block;
    padding: 20px;
    border: 2px dashed rgba(0, 212, 255, 0.3);
    border-radius: 8px;
    text-align: center;
    background: rgba(0, 212, 255, 0.05);
    transition: all 0.3s ease;
    color: #cbd5e1;
}

.file-input-wrapper input[type="file"] {
    display: none;
}

.file-input-label:hover {
    border-color: rgba(0, 212, 255, 0.6);
    background: rgba(0, 212, 255, 0.1);
}

.file-input-label .material-icons { font-size: 2rem; display: block; margin-bottom: 5px; }

.btn-submit { 
    background: #000000; 
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
    background: #333333;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.3);
}

@media (max-width: 1024px) {
    .banners-container { grid-template-columns: 1fr; }
    .form-card { position: static; }
}

/* Light Theme Overrides for Banners */
body.theme-light .banners-section h2 {
    color: #000000;
}

body.theme-light .filter-btn {
    background: #ffffff !important;
    border: 1px solid #cccccc !important;
    color: #333333 !important;
}

body.theme-light .filter-btn:hover,
body.theme-light .filter-btn.active {
    background: #f0f0f0 !important;
    border-color: #808080 !important;
    color: #000000 !important;
}

body.theme-light .banner-card {
    background: #ffffff !important;
    border: 1px solid #e0e0e0 !important;
}

body.theme-light .banner-card:hover {
    background: #f9f9f9 !important;
    border-color: #cccccc !important;
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

body.theme-light .banner-type {
    background: transparent;
    color: #666666;
}

body.theme-light .banner-type.image {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

body.theme-light .banner-type.video {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
}

body.theme-light .banner-section-badge {
    background: rgba(168, 85, 247, 0.1);
    color: #a855f7;
}

body.theme-light .banner-name {
    color: #000000;
}

body.theme-light .banner-details {
    color: #666666;
}

body.theme-light .banner-link-preview {
    color: #0066cc;
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
body.theme-light .form-group select,
body.theme-light .form-group textarea {
    background: #ffffff;
    border: 1px solid #cccccc;
    color: #000000;
}

body.theme-light .form-group input:focus,
body.theme-light .form-group select:focus,
body.theme-light .form-group textarea:focus {
    border-color: #808080;
    background: #f9f9f9;
    box-shadow: 0 0 8px rgba(128, 128, 128, 0.2);
}

body.theme-light .file-input-label {
    border: 2px dashed #cccccc;
    background: #f9f9f9;
    color: #333333;
}

body.theme-light .file-input-label:hover {
    border-color: #808080;
    background: #f0f0f0;
}

body.theme-light .btn-submit {
    background: #808080 !important;
    color: #ffffff !important;
}

body.theme-light .btn-submit:hover {
    background: #808080 !important;
    transform: none;
}

body.theme-light .empty-state {
    border: 2px dashed #cccccc;
    color: #666666;
    background: transparent;
}

/* Dark Theme Overrides */
body.theme-dark .banners-section h2 {
    color: #ffffff;
}

body.theme-dark .filter-btn {
    background: #808080 !important;
    border: 1px solid #808080 !important;
    color: #ffffff !important;
}

body.theme-dark .filter-btn:hover,
body.theme-dark .filter-btn.active {
    background: #808080 !important;
    border-color: #808080 !important;
    color: #ffffff !important;
    transform: none;
}

body.theme-dark .banner-card {
    background: #323232 !important;
    border: 1px solid #444444 !important;
}

body.theme-dark .banner-card:hover {
    background: #3d3d3d !important;
    border-color: #555555 !important;
    transform: translateX(5px);
}

body.theme-dark .banner-name {
    color: #ffffff;
}

body.theme-dark .banner-details {
    color: #b0b0b0;
}

body.theme-dark .banner-link-preview {
    color: #60a5fa;
}

body.theme-dark .form-card {
    background: #323232;
    border: 1px solid #444444;
}

body.theme-dark .form-card h3 {
    color: #ffffff;
}

body.theme-dark .form-group label {
    color: #e0e0e0;
}

body.theme-dark .form-group input,
body.theme-dark .form-group select,
body.theme-dark .form-group textarea {
    background: #1a1a1a;
    border: 1px solid #444444;
    color: #ffffff;
}

body.theme-dark .btn-submit {
    background: #808080 !important;
    color: #ffffff !important;
}

body.theme-dark .btn-submit:hover {
    background: #808080 !important;
    transform: none;
}
</style>
@endpush

@section('content')
<div class="banners-container">
    <!-- Banners List (7 parts) -->
    <div class="banners-section">
        <h2>📸 Banner Management</h2>
        
        <!-- Section Filters -->
        <div class="section-filters">
            <button class="filter-btn active" data-section="all">All Sections</button>
            <button class="filter-btn" data-section="default">Default</button>
            <button class="filter-btn" data-section="men">Men</button>
            <button class="filter-btn" data-section="women">Women</button>
            <button class="filter-btn" data-section="accessories">Accessories</button>
            <button class="filter-btn" data-section="footwear">Footwear</button>
        </div>

        <div class="banners-grid" id="bannersGrid">
            @forelse($banners as $banner)
                <div class="banner-card" data-section="{{ $banner->section ?? 'default' }}">
                    <div>
                        @if($banner->media_type === 'image')
                            <img src="{{ asset($banner->file_path) }}" alt="{{ $banner->file_name }}" class="banner-thumbnail">
                        @else
                            <video class="banner-thumbnail" controls>
                                <source src="{{ asset($banner->file_path) }}" type="video/mp4">
                            </video>
                        @endif
                    </div>
                    <div class="banner-info">
                        <div>
                            <span class="banner-type {{ $banner->media_type }}">{{ $banner->media_type }}</span>
                            <span class="banner-section-badge">{{ $banner->section ?? 'default' }}</span>
                            <div class="banner-name">{{ $banner->file_name }}</div>
                            @if($banner->banner_link)
                                <a href="{{ $banner->banner_link }}" target="_blank" class="banner-link-preview">📍 Link: {{ substr($banner->banner_link, 0, 40) }}...</a>
                            @endif
                        </div>
                        <div class="banner-details">
                            <span>📅 {{ $banner->created_at->format('M d, Y') }}</span>
                            <span>📊 Order: {{ $banner->display_order }}</span>
                            <span>Status: 
                                <form action="{{ route('admin.banners.toggle', $banner->id) }}" method="POST" style="display:inline;" onchange="this.submit()">
                                    @csrf
                                    <label class="toggle-switch">
                                        <input type="checkbox" {{ $banner->is_enabled ? 'checked' : '' }} onchange="this.form.submit()">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </form>
                            </span>
                        </div>
                    </div>
                    <div class="banner-actions">
                        <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn-icon" title="Edit" style="background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3);">
                            <span class="material-icons">edit</span>
                        </a>
                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this banner?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" title="Delete">
                                <span class="material-icons">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <span class="material-icons">image_not_supported</span>
                    <p>No banners uploaded yet. Add one using the form →</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Upload Form (3.5 parts) -->
    <div class="form-card">
        <h3>➕ Upload Banner</h3>
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label>Section *</label>
                <select name="section" required>
                    <option value="">Select Section</option>
                    <option value="default">Default Home</option>
                    <option value="men">Men</option>
                    <option value="women">Women</option>
                    <option value="accessories">Accessories</option>
                    <option value="footwear">Footwear</option>
                </select>
            </div>

            <div class="form-group">
                <label>Banner Link (Product/Category URL)</label>
                <input type="url" name="banner_link" placeholder="https://example.com/products" title="Full URL to product or category page">
            </div>

            <div class="form-group">
                <label>Display Order</label>
                <input type="number" name="display_order" value="0" min="0" step="1" title="Lower numbers appear first">
            </div>
            
            <div class="form-group">
                <label>Select File (Image or Video) *</label>
                <label class="file-input-label">
                    <span class="material-icons">cloud_upload</span>
                    <span>Click to upload or drag & drop</span>
                    <input type="file" name="file" accept="image/*,video/*" required onchange="updateFileName(this)">
                </label>
            </div>

            <div class="form-group" id="fileNameDisplay" style="display:none;">
                <label>File Name</label>
                <input type="text" id="selectedFileName" readonly disabled style="background: rgba(255,255,255,0.05); cursor: not-allowed;">
            </div>

            <button type="submit" class="btn-submit">Upload Banner</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function updateFileName(input) {
    const fileName = input.files[0]?.name || '';
    const display = document.getElementById('fileNameDisplay');
    const nameInput = document.getElementById('selectedFileName');
    
    if (fileName) {
        display.style.display = 'block';
        nameInput.value = fileName;
    } else {
        display.style.display = 'none';
    }
}

// Section filtering
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const section = this.dataset.section;
        const grid = document.getElementById('bannersGrid');
        
        // Update active filter
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Filter cards
        grid.querySelectorAll('.banner-card').forEach(card => {
            if (section === 'all' || card.dataset.section === section) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
