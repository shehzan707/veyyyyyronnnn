@extends('layouts.app')

@section('title', $product->name . ' — VEYRON')

@push('styles')
<style>
.product-detail{display:grid;grid-template-columns:0.8fr 1.2fr;gap:40px;padding:40px 0}
.product-gallery{position:sticky;top:80px}
.product-gallery img{width:80%;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,.1)}
.product-info{display:flex;flex-direction:column;gap:20px}
.product-title{font-size:2rem;font-weight:700}
.product-price{font-size:1.8rem;font-weight:700}
.product-category{color:#888}
.product-description{color:#555;line-height:1.8}

.size-selector{display:flex;gap:10px;flex-wrap:wrap}
.size-selector.hidden{display:none !important;}
.size-option{
    padding:10px 20px;
    border:2px solid #ddd;
    border-radius:8px;
    cursor:pointer;
    transition:.3s;
    position:relative;
}
.size-option span{
    display:block;
}
.size-option.disabled{
    opacity:0.4;
    cursor:not-allowed;
    position:relative;
}
.size-option.disabled::after{
    content:'';
    position:absolute;
    top:0;
    left:0;
    right:0;
    bottom:0;
    display:flex;
    align-items:center;
    justify-content:center;
}
.size-option.disabled::before{
    content:'';
    position:absolute;
    top:50%;
    left:50%;
    width:60%;
    height:2px;
    background:#999;
    transform:translate(-50%,-50%) rotate(-45deg);
    z-index:1;
}
.size-option.active{
    background:#222;
    color:#fff;
    border-color:#222;
}
.size-option.jiggle{
    animation:jiggle .4s;
}
@keyframes jiggle{
    0%,100%{transform:translateX(0)}
    25%{transform:translateX(-4px)}
    50%{transform:translateX(4px)}
    75%{transform:translateX(-4px)}
}

.size-error{
    color:#dc3545;
    font-size:.9rem;
    display:none;
    margin-top:6px;
}

.quantity-selector{display:flex;align-items:center;gap:15px}
.qty-btn{width:40px;height:40px;border:1px solid #ddd;border-radius:8px}
.qty-input{width:60px;text-align:center;border-radius:8px;border:1px solid #ddd}

.action-buttons{display:flex;gap:15px}
.btn-cart{background:#222;color:#fff;padding:15px;border-radius:8px;border:none}
.btn-wishlist{border:2px solid #222;background:#fff;padding:15px;border-radius:8px}

/* MINI CART CARD */
.mini-cart{
    position:fixed;
    top:80px;
    right:80px;
    background:#222;
    color:#fff;
    padding:6px 8px;
    border-radius:6px;
    display:flex;
    align-items:center;
    gap:6px;
    font-size:11px;
    z-index:9999;
    animation:fadeIn .3s;
}
.mini-cart img{
    width:24px;
    height:24px;
    object-fit:cover;
    border-radius:4px;
}

@keyframes fadeIn{
    from{opacity:0;transform:translateY(-5px)}
    to{opacity:1}
}

@media(max-width:768px){
    .product-detail{grid-template-columns:1fr}
}



.action-buttons{
    display:flex;
    gap:14px;
    margin-top:10px;
}

.btn-cart,
.btn-wishlist{
    flex:1;
    height:52px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    font-size:15px;
    font-weight:600;
    border-radius:10px;
    cursor:pointer;
    transition:all .25s ease;
}

.btn-cart{
    background:#222;
    color:#fff;
}

.btn-cart:hover{
    background:#000;
    transform:translateY(-1px);
}

.btn-wishlist{
    background:#fff;
    border:2px solid #222;
    color:#222;
}

.btn-wishlist:hover{
    background:#f5f5f5;
}











.quantity-selector{
    display:flex;
    align-items:center;
    gap:14px;
    margin-top:10px;
}

.qty-box{
    display:flex;
    align-items:center;
    border:1px solid #ddd;
    border-radius:999px;
    overflow:hidden;
    height:42px;
}

.qty-box button{
    width:42px;
    height:42px;
    border:none;
    background:#fff;
    font-size:18px;
    cursor:pointer;
}

.qty-box button:hover{
    background:#f2f2f2;
}

.qty-box input{
    width:50px;
    border:none;
    text-align:center;
    font-size:15px;
    outline:none;
}


.mini-cart.wishlist{
    background:#fff;
    color:black;
    border:1px solid #222;
}

.mini-cart.wishlist span{
    color:#222;
    font-weight:600;
}





</style>
@endpush

@section('content')
<div class="container">

<div class="product-detail">
    <div class="product-gallery">
        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
    </div>

    <div class="product-info">
        <span class="product-category">{{ ucwords(str_replace('-', ' ', $product->category)) }}</span>
        <h1 class="product-title">{{ $product->name }}</h1>
        <span class="product-price">₹{{ number_format($product->price,2) }}</span>

        <form id="addToCartForm">
            @csrf
            <input type="hidden" value="{{ $product->id }}" id="productId">

            @php
                $sizes = json_decode($product->sizes,true);
                if(!is_array($sizes)){
                    $sizes = array_map('trim', explode(',', $product->sizes));
                }
                // Get stock information for each size
                $stockData = [];
                foreach($sizes as $size) {
                    $sizeVariant = $product->sizeVariants()->where('size', $size)->first();
                    $stockData[$size] = $sizeVariant ? $sizeVariant->stock : 0;
                }
                // Check if size is "B" (one size only)
                $isSingleSizeB = count($sizes) === 1 && $sizes[0] === 'B';
            @endphp

          @if(!$isSingleSizeB)
    <label><strong>Select Size</strong></label>
    <br>
@endif

<div class="size-selector{{ $isSingleSizeB ? ' hidden' : '' }}">
    @foreach($sizes as $size)
        @php
            $classStr = $stockData[$size] == 0 ? 'disabled' : '';
            if($isSingleSizeB) {
                $classStr .= ' active';
            }
        @endphp

        <div class="size-option {{ $classStr }}"
             data-size="{{ $size }}"
             data-stock="{{ $stockData[$size] }}"
             @if($stockData[$size] == 0) data-disabled="true" @endif>
            {{ $size }}
        </div>
    @endforeach
</div>

            <input type="hidden" id="selectedSize" value="{{ $isSingleSizeB ? 'B' : '' }}">

            <div class="size-error"></div>
            <div id="stockWarning" style="color:#dc3545; font-size:0.9rem; display:none; margin-top:10px; font-weight:600;"></div>

            <div class="quantity-selector">
    <span style="font-weight:600;">Quantity</span>

    <div class="qty-box">
        <button type="button" onclick="changeQty(-1)">−</button>
        <input type="number" id="qtyInput" value="1" min="1">
        <button type="button" onclick="changeQty(1)">+</button>
    </div>
</div>
            <div id="quantityWarning" style="color:#dc3545; font-size:0.9rem; display:none; margin-top:10px; font-weight:600;"></div>


            <div class="action-buttons">
                <button type="submit" class="btn-cart">Add to Bag</button>
                <button type="button" class="btn-wishlist" id="wishlistBtn">
     Wishlist
</button>

            </div>
        </form>

        <div class="product-description">
            <h3>Description</h3>
            <p>{{ $product->description }}</p>
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
let selectedSize = null;
let selectedStock = 0;

// Get all size options with their stock data
const sizeOptions = document.querySelectorAll('.size-option');
const stockWarning = document.getElementById('stockWarning');
const quantityWarning = document.getElementById('quantityWarning');
const qtyInput = document.getElementById('qtyInput');

// Auto-select size "B" if it's the only size
const singleSizeB = document.getElementById('selectedSize');
if (singleSizeB && singleSizeB.value === 'B') {
    const bSizeOption = document.querySelector('[data-size="B"]');
    if (bSizeOption) {
        selectedSize = 'B';
        selectedStock = parseInt(bSizeOption.dataset.stock);
        // Mark as active (for visual consistency even though it's hidden)
        bSizeOption.classList.add('active');
    }
}

// Handle size selection
sizeOptions.forEach(el=>{
    el.addEventListener('click',()=>{
        // Check if size is disabled (out of stock)
        if(el.classList.contains('disabled')){
            return; // Don't allow clicking disabled sizes
        }
        
        sizeOptions.forEach(s=>s.classList.remove('active'));
        el.classList.add('active');
        selectedSize = el.dataset.size;
        selectedStock = parseInt(el.dataset.stock);
        document.querySelector('.size-error').style.display='none';
        stockWarning.style.display='none';
        quantityWarning.style.display='none';
        
        // Validate quantity when size is changed
        validateQuantity();
    });
});

// Function to validate quantity against stock
function validateQuantity() {
    const quantity = parseInt(qtyInput.value);
    
    if (!selectedSize) {
        quantityWarning.style.display = 'none';
        return true;
    }
    
    if (selectedStock <= 0) {
        quantityWarning.style.display = 'block';
        quantityWarning.innerHTML = `❌ This size is currently out of stock`;
        qtyInput.max = 0;
        return false;
    }
    
    if (quantity > selectedStock) {
        quantityWarning.style.display = 'block';
        quantityWarning.innerHTML = `⚠️ ${selectedSize} size only has ${selectedStock} items in stock. Please select ${selectedStock} or less.`;
        return false;
    } else {
        quantityWarning.style.display = 'none';
        qtyInput.max = selectedStock;
        return true;
    }
}

// Listen to quantity input changes
qtyInput.addEventListener('input', function() {
    // Allow only positive numbers
    if (this.value < 1) {
        this.value = 1;
    }
    validateQuantity();
});

// Listen to quantity input changes via buttons
function changeQty(val){
    const input=document.getElementById('qtyInput');
    const newValue = Math.max(1, parseInt(input.value)+val);
    input.value = newValue;
    validateQuantity();
}

document.getElementById('addToCartForm').addEventListener('submit',function(e){
    e.preventDefault();

    if(!selectedSize){
        document.querySelector('.size-error').style.display='block';
        document.querySelector('.size-error').innerHTML = 'Please select a size';
        sizeOptions.forEach(s=>{
            s.classList.add('jiggle');
            setTimeout(()=>s.classList.remove('jiggle'),400);
        });
        return;
    }

    // Final validation before submit
    const quantity = parseInt(document.getElementById('qtyInput').value);
    
    if (selectedStock <= 0) {
        quantityWarning.style.display = 'block';
        quantityWarning.innerHTML = `❌ This size is currently out of stock`;
        return;
    }
    
    if (quantity > selectedStock) {
        quantityWarning.style.display = 'block';
        quantityWarning.innerHTML = `⚠️ ${selectedSize} size only has ${selectedStock} items in stock. Please select ${selectedStock} or less.`;
        return;
    }

    // Get CSRF token from form
    const csrfToken = document.querySelector('input[name="_token"]').value;
    
    fetch(`/cart/add/{{ $product->id }}`,{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body:JSON.stringify({
            quantity:document.getElementById('qtyInput').value,
            size:selectedSize
        })
    }).then(res => {
        if (!res.ok) {
            return res.text().then(text => {
                console.error('Server error response:', text);
                try {
                    const data = JSON.parse(text);
                    throw new Error(data.message || 'Failed to add product to cart');
                } catch (parseError) {
                    // If it's not JSON, it might be HTML error page
                    if (text.includes('CSRF')) {
                        throw new Error('Security validation failed. Please refresh and try again.');
                    }
                    throw new Error('An error occurred on the server. Please try again.');
                }
            });
        }
        return res.json();
    }).then(data => {
        if (data.success) {
            showAddToBagMiniCard();
        }
    }).catch(error => {
        console.error('Error:', error);
        quantityWarning.style.display = 'block';
        quantityWarning.innerHTML = `❌ ${error.message}`;
    });
});


document.getElementById('wishlistBtn').addEventListener('click', function () {

    fetch(`/wishlist/add/{{ $product->id }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => {
        if(res.status === 401){
            window.location.href = "{{ route('login') }}";
            return;
        }
        return res.json();
    })
    .then(data => {
        showWishlistMiniCard();
        highlightWishlistButton();
    });
});

function showWishlistMiniCard(){
    const card = document.createElement('div');
    card.className = 'mini-cart wishlist';
    card.innerHTML = `
        <img src="{{ asset($product->image) }}">
        <span>Added to Wishlist</span>
    `;
    document.body.appendChild(card);

    setTimeout(() => card.remove(), 3000);
}

function highlightWishlistButton(){
    const btn = document.getElementById('wishlistBtn');
    btn.style.background = '#222';
    btn.style.color = '#fff';
    btn.innerHTML = '❤ Wishlisted';
}




function showAddToBagMiniCard(){
    const card = document.createElement('div');
    card.className = 'mini-cart';
    card.innerHTML = `
        <img src="{{ asset($product->image) }}">
        <span>Added to Bag</span>
    `;
    document.body.appendChild(card);

    setTimeout(() => card.remove(), 3000);
}

// Initialize size and quantity from URL query parameters
window.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const sizeParam = urlParams.get('size');
    const qtyParam = urlParams.get('qty');
    
    // Set size from query parameter if provided
    if (sizeParam) {
        const sizeElement = document.querySelector(`[data-size="${sizeParam}"]`);
        if (sizeElement && !sizeElement.classList.contains('disabled')) {
            sizeElement.click();
        }
    }
    
    // Set quantity from query parameter if provided
    if (qtyParam) {
        const qtyInput = document.getElementById('qtyInput');
        if (qtyInput) {
            const qty = Math.max(1, parseInt(qtyParam));
            qtyInput.value = qty;
            validateQuantity();
        }
    }
});

</script>
@endpush
