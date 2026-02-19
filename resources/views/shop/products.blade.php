@extends('layouts.app')

@section('title', 'VEYRON — Shop')

@push('styles')
<style>
/* Layout */
.products-page {
    position: relative;
    display: flex;
}

.products-page .sidebar {
    position: fixed;
    left: 0;
    top: 70px;
    width: 340px;
    height: calc(100vh - 70px);
    background: #fff;
    box-shadow: 4px 0 20px rgba(0,0,0,0.12);
    overflow-y: auto;
    padding: 30px;
    transform: translateX(-100%);
    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 50;
}

.products-page .sidebar::-webkit-scrollbar {
    width: 6px;
}

.products-page .sidebar::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.products-page .sidebar::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.products-page .sidebar::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.products-page .sidebar.active {
    transform: translateX(0);
}

.products-page .sidebar h3 {
    font-size: 1.25rem;
    color: #222;
    font-weight: 700;
    border-bottom: 3px solid #222;
    padding-bottom: 15px;
    margin-bottom: 25px;
}

/* Gender/Type Selection */
.gender-options {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.gender-options h4 {
    font-size: 0.8rem;
    color: #555;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 15px;
}

.gender-option {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    cursor: pointer;
}

.gender-option input[type="radio"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
    margin-right: 12px;
    accent-color: #e91e63;
}

.gender-option label {
    cursor: pointer;
    font-size: 0.95rem;
    color: #333;
    font-weight: 500;
    margin: 0;
}

.gender-option input[type="radio"]:checked + label {
    font-weight: 700;
    color: #222;
}

/* Categories with Checkboxes */
.categories-section {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.categories-section h4 {
    font-size: 0.8rem;
    color: #555;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 15px;
}

.category-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.category-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    margin-right: 10px;
    accent-color: #222;
}

.category-item label {
    cursor: pointer;
    font-size: 0.9rem;
    color: #333;
    margin: 0;
    flex: 1;
    display: flex;
    justify-content: space-between;
}

.category-item input[type="checkbox"]:checked + label {
    font-weight: 700;
    color: #222;
}

.category-count {
    font-size: 0.8rem;
    color: #999;
}

.show-more-btn {
    background: none;
    border: none;
    color: #e91e63;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    margin-top: 8px;
    padding: 0;
}

/* Filter */
.products-page .filter {
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f0f0f0;
}

/* Range Slider */
.price-range-container {
    margin-bottom: 20px;
}

.price-range-inputs {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.price-range-inputs input {
    flex: 0.7;
    max-width: 110px;
    padding: 10px 12px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    font-size: 0.9rem;
    background: #f8f8f8;
    transition: all 0.3s ease;
}

.price-range-inputs input:focus {
    border-color: #222;
    background: #fff;
    outline: none;
}

.price-range-slider {
    position: relative;
    height: 6px;
    margin: 20px 0;
}

.price-range-slider input[type="range"] {
    position: absolute;
    width: 100%;
    height: 6px;
    top: 0;
    margin: 0;
    padding: 0;
    pointer-events: none;
    border-radius: 3px;
    background: none;
    -webkit-appearance: none;
}

.price-range-slider input[type="range"]::-webkit-slider-thumb {
    pointer-events: auto;
    -webkit-appearance: none;
    appearance: none;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #222;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(34,34,34,0.3);
    transition: all 0.2s ease;
}

.price-range-slider input[type="range"]::-webkit-slider-thumb:hover {
    transform: scale(1.15);
    box-shadow: 0 4px 12px rgba(34,34,34,0.4);
}

.price-range-slider input[type="range"]::-moz-range-thumb {
    pointer-events: auto;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #222;
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 8px rgba(34,34,34,0.3);
    transition: all 0.2s ease;
}

.price-range-slider input[type="range"]::-moz-range-thumb:hover {
    transform: scale(1.15);
    box-shadow: 0 4px 12px rgba(34,34,34,0.4);
}

.price-range-track {
    position: absolute;
    height: 6px;
    background: #e0e0e0;
    border-radius: 3px;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1;
}

.price-range-fill {
    position: absolute;
    height: 6px;
    background: #222;
    border-radius: 3px;
    top: 0;
    z-index: 2;
}

.products-page .filter-btn {
    width: 100%;
    padding: 14px 20px;
    background: #222;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 20px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.products-page .filter-btn:hover {
    background: #000;
    box-shadow: 0 6px 16px rgba(0,0,0,0.2);
    transform: translateY(-2px);
}

.products-page .filter-btn:active {
    transform: translateY(0);
}

.clear-filter-btn {
    width: 100%;
    padding: 12px 20px;
    background: #222;
    color: #f5f5f5;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 15px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.clear-filter-btn:hover {
    background: #000;
    color: #fff;
}

/* Filter Toggle Button */
.filter-toggle {
    position: fixed;
    top: 90px;
    left: 30px;
    z-index: 60;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.filter-toggle button {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    background: #fff;
    color: #222;
    border: none !important;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
}

.filter-toggle button:hover {
    background: #f5f5f5;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    transform: scale(1.05);
}

.filter-toggle.hidden {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

/* Main Content */
.products-page .main-content {
    flex: 1;
    width: 100%;
    transition: margin-left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    margin-left: 0;
    padding: 0;
}

.products-page .sidebar.active ~ .main-content {
    margin-left: 320px;
}

/* Products Container */
.products-page .products-container {
    padding: 30px 40px;
    max-width: 1600px;
    margin: 0 auto;
}

/* Category Title Display */
.category-curated-title {
    font-family: 'Georgia', 'Poster', serif;
    font-size: 2.2rem;
    font-weight: 900;
    letter-spacing: 3px;
    margin-bottom: 30px;
    margin-top: 0;
    text-transform: uppercase;
}

.category-curated-title.men {
    color: #27C0FE;
}

.category-curated-title.women {
    color: #FF69B4;
}

.category-curated-title.footwear {
    color: #20B2AA;
}

.category-curated-title.accessories {
    color: #9ba4c2;
}

/* Product Count Display */
.product-count-display {
    font-size: 0.95rem;
    color: #666;
    margin-bottom: 20px;
    font-weight: 500;
    padding: 12px 0;
    border-bottom: 1px solid #e0e0e0;
}

.product-count-display strong {
    color: #222;
    font-weight: 700;
}

/* Sticky Header */
/* Header is now fixed globally in main layout */

/* Product Count Display */
.product-count-display {
    font-size: 0.95rem;
    color: #666;
    margin-bottom: 20px;
    font-weight: 500;
    padding: 12px 0;
    border-bottom: 1px solid #e0e0e0;
}

.product-count-display strong {
    color: #222;
    font-weight: 700;
}

/* Products Grid */
.products-page .products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 24px;
    width: 100%;
}

/* Product Card */
.products-page .product-card {
    background: transparent;
    border-radius: 0;
    overflow: hidden;
    position: relative;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}

.products-page .product-card:hover {
    transform: scale(1.01);
}

.products-page .product-card a {
    text-decoration: none;
    color: inherit;
    display: block;
}

/* Product Image Container */
.products-page .product-image {
    position: relative;
    overflow: hidden;
    aspect-ratio: 0.75;
    background: #f5f5f5;
}

.products-page .product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.products-page .product-card:hover img {
    /* No hover effect */
}

/* Hover Overlay */
.products-page .product-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: none;
    align-items: flex-end;
    justify-content: center;
    gap: 8px;
    padding: 16px;
    opacity: 0;
    transition: opacity 0.35s ease;
}

.products-page .product-card:hover .product-overlay {
    display: none;
}

/* Overlay Buttons */
.products-page .overlay-btn {
    flex: 1;
    padding: 10px 12px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.products-page .overlay-btn:hover {
    /* Removed hover effect */
}

.products-page .add-btn {
    background: #fff;
    color: #222;
    flex: 1.5;
    font-weight: 700;
    display: none;
}

.products-page .add-btn:hover {
    /* No hover effect */
}
.products-page .product-card:hover .wish-btn {
    opacity: 1;
    visibility: visible;
    transform: scale(1);
    transition: all 0.3s ease;
}


.products-page .wish-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #fff;
    color: #222;
    width: 40px;
    height: 40px;
    padding: 0;
    border: none;
    border-radius: 6px;
    display: flex;
    opacity: 0;
    visibility: hidden;
    transform: scale(0.8);
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    flex: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.products-page .wish-btn:hover {
    /* Removed hover effect */
}

/* Product Info */
.products-page .product-info {
    padding: 14px 14px 16px;
    transition: all 0.3s ease;
    background: #f9f9f9;
}

.products-page .product-card:hover .product-info {
    /* No hover effect */
}

/* Product Category */
.products-page .product-category {
    font-size: 0.85rem;
    color: #888;
    margin-bottom: 8px;
    font-weight: 400;
    line-height: 1.4;
}

.products-page .product-name {
    font-size: 0.9rem;
    font-weight: 600;
    color: #222;
    margin-bottom: 6px;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: font-size 0.3s ease;
}

.products-page .product-card:hover .product-name {
    /* No hover effect */
}

.products-page .product-price {
    font-size: 1rem;
    font-weight: 700;
    color: #000;
    letter-spacing: -0.5px;
    transition: font-size 0.3s ease;
}

.products-page .product-card:hover .product-price {
    /* No hover effect */
}    /* Removed hover effect */
}

.products-page .product-price-original {
    font-size: 0.8rem;
    color: #999;
    text-decoration: line-through;
    margin-right: 6px;
}

/* Empty State */
.products-page .no-products {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 40px;
    color: #999;
    font-size: 1.1rem;
}

.products-page .no-products a {
    color: #222;
    text-decoration: none;
    font-weight: 600;
    border-bottom: 2px solid #222;
    margin-top: 15px;
    display: inline-block;
    padding: 5px 0;
}

.products-page .no-products a:hover {
    
    
}

/* Close Filter Overlay */
.filter-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.4);
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.35s ease;
    z-index: 40;
}

.filter-overlay.active {
    opacity: 1;
    pointer-events: auto;
}

/* Responsive */
@media (max-width: 1024px) {
    .products-page .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 18px;
    }
    
    .products-page .products-container {
        padding: 25px 25px;
    }
}

@media (max-width: 768px) {
    .products-page .sidebar {
        width: 280px;
    }
    
    .filter-toggle {
        top: 75px;
        left: 15px;
    }
    
    .filter-toggle button {
        width: 45px;
        height: 45px;
        font-size: 22px;
    }
    
    .products-page .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 16px;
    }
    
    .products-page .products-container {
        padding: 20px 15px;
    }
}

@media (max-width: 480px) {
    .products-page .sidebar {
        width: 100%;
    }
    
    .products-page .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .products-page .products-container {
        padding: 15px 12px;
    }
    
    .products-page .overlay-btn {
        padding: 8px 8px;
        font-size: 0.7rem;
    }
}
</style>
@endpush

@section('content')
<div class="products-page" id="productsPage">
    <!-- Filter Overlay -->
    <div class="filter-overlay" id="filterOverlay"></div>

    <!-- Filter Toggle Button -->
    <div class="filter-toggle">
        <button type="button" id="filterBtn" title="Filters">
            <span class="material-icons">tune</span>
        </button>
    </div>

    <!-- Sidebar Filter -->
    <aside class="sidebar" id="filterSidebar">
        <h3>Filter Products</h3>
        <div id="filterContainer">
            
            <!-- Gender/Type Selection -->
            <div class="gender-options">
                <h4>Category Type</h4>
                <div class="gender-option">
                    <input type="radio" id="type_men" name="gender_type" value="men" onchange="updateCategories()">
                    <label for="type_men">Men</label>
                </div>
                <div class="gender-option">
                    <input type="radio" id="type_women" name="gender_type" value="women" onchange="updateCategories()">
                    <label for="type_women">Women</label>
                </div>
                <div class="gender-option">
                    <input type="radio" id="type_accessories" name="gender_type" value="accessories" onchange="updateCategories()">
                    <label for="type_accessories">Accessories</label>
                </div>
                <div class="gender-option">
                    <input type="radio" id="type_footwear" name="gender_type" value="footwear" onchange="updateCategories()">
                    <label for="type_footwear">Footwear</label>
                </div>
            </div>

            <!-- Categories Section -->
            <div class="categories-section">
                <h4>Categories</h4>
                <div id="categoriesContainer">
                    <!-- Loaded dynamically -->
                </div>
            </div>

            <!-- Price Range -->
            <div class="filter">
                <label>Price Range (₹)</label>
                <div class="price-range-container">
                    <div class="price-range-inputs">
                        <input type="number" id="minPriceInput" value="0" placeholder="Min" min="0" onchange="applyFilters()">
                        <input type="number" id="maxPriceInput" value="100000" placeholder="Max" min="0" onchange="applyFilters()">
                    </div>
                    
                    <div class="price-range-slider">
                        <div class="price-range-track"></div>
                        <div class="price-range-fill" id="priceRangeFill"></div>
                        <input type="range" id="minRange" min="0" max="100000" value="0" step="100" oninput="updateRangeSlider()">
                        <input type="range" id="maxRange" min="0" max="100000" value="100000" step="100" oninput="updateRangeSlider()">
                    </div>
                </div>
            </div>

            <!-- Clear Filters Button -->
            <button type="button" class="clear-filter-btn" onclick="clearAllFilters()">Clear</button>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <div class="products-container">
            @php
                $genderTitles = [
                    'men' => 'CURATED MENSWEAR',
                    'women' => 'CURATED WOMENSWEAR',
                    'footwear' => 'CURATED FOOTWEAR',
                    'accessories' => 'CURATED ACCESSORIES'
                ];
                $genderFromUrl = request()->input('gender');
                $categoryTitle = $genderTitles[$genderFromUrl] ?? null;
            @endphp

            @if($categoryTitle)
                <h1 class="category-curated-title {{ $genderFromUrl }}">{{ $categoryTitle }}</h1>
            @endif

            <!-- Product Count Display -->
            <div class="product-count-display">
                Showing <strong id="productCountDisplay">{{ $products->count() }}</strong> products
            </div>
            
            <!-- Products Grid -->
            <div class="products-grid">
                @forelse($products as $product)
                    <div class="product-card">
                        <a href="{{ route('products.show', $product->id) }}">
                            <div class="product-image">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">

                                <div class="product-overlay">
                                    <button class="overlay-btn add-btn" onclick="event.stopPropagation(); addToCart({{ $product->id }})" type="button">Add to Bag</button>
                                </div>
                                
                                <button class="overlay-btn wish-btn" onclick="event.stopPropagation(); event.preventDefault(); addToWishlist({{ $product->id }}); return false;" type="button" title="Add to Wishlist">
                                    <span class="material-icons" style="font-size: 18px;">favorite_border</span>
                                </button>
                            </div>

                            <div class="product-info">
                                <div class="product-category">
                                    @if($product->categoryModel)
                                        @if($product->categoryModel->parent)
                                            {{ $product->categoryModel->parent->name }} - {{ $product->categoryModel->name }}
                                        @else
                                            {{ $product->categoryModel->name }}
                                        @endif
                                    @else
                                        Product
                                    @endif
                                </div>
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-price">₹{{ number_format($product->price, 0) }}</div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="no-products">
                        <h2 style="font-weight:700; font-size: 1.5rem;">No products found</h2>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<script>
const filterBtn = document.getElementById('filterBtn');
const filterSidebar = document.getElementById('filterSidebar');
const filterOverlay = document.getElementById('filterOverlay');
const filterToggle = document.querySelector('.filter-toggle');

// Toggle filter sidebar
filterBtn.addEventListener('click', () => {
    filterSidebar.classList.toggle('active');
    filterOverlay.classList.toggle('active');
    filterToggle.classList.toggle('hidden');
});

// Close filter on overlay click
filterOverlay.addEventListener('click', () => {
    filterSidebar.classList.remove('active');
    filterOverlay.classList.remove('active');
    filterToggle.classList.remove('hidden');
});

// Close filter when clicking inside filter
document.addEventListener('click', (e) => {
    if (!e.target.closest('.sidebar') && !e.target.closest('#filterBtn') && !e.target.closest('.show-more-btn')) {
        filterSidebar.classList.remove('active');
        filterOverlay.classList.remove('active');
        filterToggle.classList.remove('hidden');
    }
});

// Range Slider Handler
const minRange = document.getElementById('minRange');
const maxRange = document.getElementById('maxRange');
const minPriceInput = document.getElementById('minPriceInput');
const maxPriceInput = document.getElementById('maxPriceInput');
const priceRangeFill = document.getElementById('priceRangeFill');

// Dynamic category data from database
const categoryData = @json($categoryGroups ?? []);

function updateRangeSlider() {
    let minVal = parseInt(minRange.value);
    let maxVal = parseInt(maxRange.value);
    
    if (minVal > maxVal) {
        [minVal, maxVal] = [maxVal, minVal];
        minRange.value = minVal;
        maxRange.value = maxVal;
    }
    
    // Update input fields
    minPriceInput.value = minVal;
    maxPriceInput.value = maxVal;
    
    // Calculate and update fill position
    const minPercent = (minVal / 100000) * 100;
    const maxPercent = (maxVal / 100000) * 100;
    priceRangeFill.style.left = minPercent + '%';
    priceRangeFill.style.right = (100 - maxPercent) + '%';
    
    applyFilters();
}

// Update range sliders when input fields change
minPriceInput.addEventListener('change', function() {
    let minVal = parseInt(this.value) || 0;
    let maxVal = parseInt(maxPriceInput.value) || 100000;
    
    if (minVal > maxVal) {
        minVal = maxVal;
        this.value = minVal;
    }
    
    minRange.value = minVal;
    updateRangeSlider();
});

maxPriceInput.addEventListener('change', function() {
    let maxVal = parseInt(this.value) || 100000;
    let minVal = parseInt(minPriceInput.value) || 0;
    
    if (maxVal < minVal) {
        maxVal = minVal;
        this.value = maxVal;
    }
    
    maxRange.value = maxVal;
    updateRangeSlider();
});

// Update categories based on selected gender
function updateCategories() {
    const selectedType = document.querySelector('input[name="gender_type"]:checked')?.value;
    const container = document.getElementById('categoriesContainer');
    
    if (!selectedType) {
        container.innerHTML = '<p style="color: #999; font-size: 0.9rem;">Select a category type first</p>';
        return;
    }
    
    const categories = categoryData[selectedType] || [];
    let html = '';
    
    if (categories.length === 0) {
        container.innerHTML = '<p style="color: #999; font-size: 0.9rem;">No categories available</p>';
        applyFilters();
        return;
    }
    
    // Show categories with hierarchical structure
    categories.forEach(cat => {
        if (cat.isHeader) {
            // Header category - no checkbox, just a label
            html += `
                <div style="margin: 15px 0 10px 0; font-weight: 700; color: #555; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    ${cat.name}
                </div>
            `;
            
            // Add children under this header
            cat.children.forEach(child => {
                html += `
                    <div class="category-item" style="margin-left: 10px;">
                        <input type="checkbox" id="cat_${child.name.replace(/\s/g, '_')}" 
                               value="${child.name}" onchange="applyFilters()">
                        <label for="cat_${child.name.replace(/\s/g, '_')}">
                            <span>${child.name}</span>
                            <span class="category-count">(${child.count})</span>
                        </label>
                    </div>
                `;
            });
        } else {
            // Direct category - regular checkbox
            html += `
                <div class="category-item">
                    <input type="checkbox" id="cat_${cat.name.replace(/\s/g, '_')}" 
                           value="${cat.name}" onchange="applyFilters()">
                    <label for="cat_${cat.name.replace(/\s/g, '_')}">
                        <span>${cat.name}</span>
                        <span class="category-count">(${cat.count})</span>
                    </label>
                </div>
            `;
        }
    });
    
    container.innerHTML = html;
    applyFilters();
}

// Apply filters with real-time updates
function applyFilters() {
    const selectedType = document.querySelector('input[name="gender_type"]:checked')?.value || '';
    const selectedCategories = Array.from(document.querySelectorAll('#categoriesContainer input[type="checkbox"]:checked'))
        .map(cb => cb.value);
    const minPrice = parseInt(minPriceInput.value) || 0;
    const maxPrice = parseInt(maxPriceInput.value) || 100000;
    
    // Save current scroll position
    const scrollPosition = window.scrollY;
    
    // Build query string
    let params = new URLSearchParams();
    if (selectedType) params.append('gender', selectedType);
    selectedCategories.forEach(cat => params.append('categories[]', cat));
    params.append('min_price', minPrice);
    params.append('max_price', maxPrice);
    
    // Update products via AJAX
    const url = `/products?${params.toString()}`;
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const currentGrid = document.querySelector('.products-grid');
        currentGrid.innerHTML = '';
        
        if (!data.products || data.products.length === 0) {
            currentGrid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;"><h3 style="color: #999;">No products found</h3></div>';
        } else {
            data.products.forEach(product => {
                const productCard = `
                    <div class="product-card">
                        <a href="/products/${product.id}">
                            <div class="product-image">
                                <img src="${product.image}" alt="${product.name}">
                            </div>
                            <div class="product-info" style="padding: 12px 0;">
                                <h4 style="margin: 0 0 4px 0; font-size: 0.95rem; font-weight: 600; color: #222; width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${product.name}</h4>
                                <p style="margin: 0 0 6px 0; color: #888; font-size: 0.8rem;">${product.category}</p>
                                <p style="margin: 0; color: #666; font-size: 0.85rem;">₹${parseFloat(product.price).toLocaleString('en-IN')}</p>
                            </div>
                        </a>
                    </div>
                `;
                currentGrid.innerHTML += productCard;
            });
        }
        
        // Update product count display
        const countDisplay = document.getElementById('productCountDisplay');
        if (countDisplay) {
            countDisplay.textContent = data.products ? data.products.length : 0;
        }
        
        // Update URL without full reload
        window.history.replaceState({}, '', url);
        
        // Restore scroll position after content is updated
        window.scrollTo(0, scrollPosition);
    })
    .catch(error => console.error('Error:', error));
}

// Clear all filters
function clearAllFilters() {
    // Clear radio buttons
    document.querySelectorAll('input[name="gender_type"]').forEach(r => r.checked = false);
    
    // Clear checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    
    // Reset price range
    minRange.value = 0;
    maxRange.value = 100000;
    minPriceInput.value = 0;
    maxPriceInput.value = 100000;
    updateRangeSlider();
    
    // Clear categories display
    document.getElementById('categoriesContainer').innerHTML = 
        '<p style="color: #999; font-size: 0.9rem;">Select a category type first</p>';
    
    // Reload all products
    window.location.href = '{{ route("products.index") }}';
}

// Auto-apply filters from URL parameters on page load
document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const genderFromURL = params.get('gender');
    const categorySlugFromURL = params.get('category');
    const categoriesFromURL = params.getAll('categories[]');
    
    // If gender is provided, select it first
    if (genderFromURL) {
        const genderRadio = document.querySelector(`input[name="gender_type"][value="${genderFromURL}"]`);
        if (genderRadio) {
            genderRadio.checked = true;
            
            // Call updateCategories to load the category list for this gender
            if (typeof updateCategories === 'function') {
                updateCategories();
            }
            
            // If categories array is provided, apply them after categories load
            if (categoriesFromURL && categoriesFromURL.length > 0) {
                // Small delay to allow updateCategories to render
                setTimeout(() => {
                    applyFiltersFromURL(categoriesFromURL);
                }, 200);
            } else if (categorySlugFromURL) {
                // If category slug is also provided, apply it after categories load
                setTimeout(() => {
                    applyFilterFromURL(categorySlugFromURL);
                }, 200);
            }
        }
    } else if (categorySlugFromURL) {
        // If only category is provided (no gender), try to find it from categoryData
        applyFilterFromURL(categorySlugFromURL);
    } else if (categoriesFromURL && categoriesFromURL.length > 0) {
        // If only categories array is provided (no gender)
        applyFiltersFromURL(categoriesFromURL);
    }
});

// Helper function to apply multiple filters from categories[] array
function applyFiltersFromURL(categoryNamesToCheck) {
    const categoryCheckboxes = document.querySelectorAll('#categoriesContainer input[type="checkbox"]');
    
    if (categoryCheckboxes.length === 0) {
        // Categories not yet loaded, retry
        setTimeout(() => {
            applyFiltersFromURL(categoryNamesToCheck);
        }, 100);
        return;
    }
    
    let anyFound = false;
    
    // Check all checkboxes that match the category names from URL
    categoryCheckboxes.forEach(cb => {
        if (categoryNamesToCheck.includes(cb.value)) {
            cb.checked = true;
            anyFound = true;
        }
    });
    
    // Apply filters if any categories were found
    if (anyFound && typeof applyFilters === 'function') {
        applyFilters();
    }
}

// Helper function to apply filter by category slug
function applyFilterFromURL(categorySlugFromURL) {
    const categoryCheckboxes = document.querySelectorAll('#categoriesContainer input[type="checkbox"]');
    
    if (categoryCheckboxes.length === 0) {
        // Categories not yet loaded, retry
        setTimeout(() => {
            applyFilterFromURL(categorySlugFromURL);
        }, 100);
        return;
    }
    
    // Find category name from categoryData using the slug
    let categoryNameToFind = null;
    
    // Search through all category groups to find the matching slug
    for (const genderType in categoryData) {
        const categories = categoryData[genderType] || [];
        for (const cat of categories) {
            if (cat.slug === categorySlugFromURL) {
                categoryNameToFind = cat.name;
                break;
            }
            // Also check children
            if (cat.children && cat.children.length > 0) {
                for (const child of cat.children) {
                    if (child.slug === categorySlugFromURL) {
                        categoryNameToFind = child.name;
                        break;
                    }
                }
            }
            if (categoryNameToFind) break;
        }
        if (categoryNameToFind) break;
    }
    
    // Find and check the category checkbox
    if (categoryNameToFind) {
        let found = false;
        categoryCheckboxes.forEach(cb => {
            // Match by value (category name)
            if (cb.value === categoryNameToFind) {
                cb.checked = true;
                found = true;
            }
        });
        
        // Apply filters if category was found
        if (found && typeof applyFilters === 'function') {
            applyFilters();
        }
    }
}

// Add to Cart Function
function addToCart(productId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            quantity: 1,
            size: 'M' // Default size - can be customized
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success feedback
            showNotification('Added to bag!', 'success');
            // Optional: Update cart badge
            updateCartBadge();
        } else {
            showNotification(data.message || 'Failed to add to bag', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding to bag', 'error');
    });
}

// Add to Wishlist Function
function addToWishlist(productId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/wishlist/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 401) {
                showNotification('Please login to add to wishlist', 'error');
                setTimeout(() => {
                    window.location.href = '{{ route("login") }}';
                }, 1500);
                return;
            }
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'Added to wishlist!', 'success');
        } else {
            showNotification(data.message || 'Failed to add to wishlist', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding to wishlist', 'error');
    });
}

// Notification Function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        padding: 14px 20px;
        background: #222;
        color: white;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        font-size: 0.9rem;
        font-weight: 500;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Update Cart Badge
function updateCartBadge() {
    const cartLink = document.querySelector('a[href*="/cart"]');
    if (cartLink) {
        const badge = cartLink.querySelector('.cart-badge');
        if (badge) {
            const currentCount = parseInt(badge.textContent) || 0;
            badge.textContent = currentCount + 1;
        } else {
            const newBadge = document.createElement('span');
            newBadge.className = 'cart-badge';
            newBadge.textContent = '1';
            cartLink.appendChild(newBadge);
        }
    }
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    .wishlist-active .material-icons {
        color: #e31b1b;
    }
`;
document.head.appendChild(style);
</script>
@endsection
