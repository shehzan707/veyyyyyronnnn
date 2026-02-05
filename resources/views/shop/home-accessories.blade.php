@extends('layouts.app')

@section('title', 'VEYRON — Accessories')

@push('styles')
<style>
:root{
    --black:#080808;
    --dark:#121212;
    --mid:#7a7a7a;
    --light:#ededed;
    --white:#ffffff;
}

*{box-sizing:border-box}
body{
    margin:0;
    font-family:'Poppins',system-ui,sans-serif;
    background:var(--white);
    color:var(--black);
}

.home-container{
    max-width:100%;
    margin:0;
    padding:2px;
}

.subcategory-group{
    margin:6rem 0 8rem;
    padding:0 2.2rem;
}

.subcategory-group-title{
    font-size:3.2rem;
    font-weight:900;
    letter-spacing:5px;
    text-transform:uppercase;
    margin-bottom:4.8rem;
    position:relative;
    color:#000;
}

.subcategory-group-title::before{
    content:'VEYRON';
    position:absolute;
    top:-2.4rem;
    left:0;
    font-size:7.2rem;
    font-weight:900;
    letter-spacing:10px;
    color:rgba(0,0,0,0.12);
    pointer-events:none;
}

.subcategory-group-title::after{
    content:'';
    position:absolute;
    left:0;
    bottom:-18px;
    width:90px;
    height:3px;
    background:#000;
}

.subcategory-cards-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:3.4rem;
}

.subcategory-card{
    background:#fff;
    border:1px solid #e0e0e0;
    border-radius:0;
    overflow:hidden;
    text-decoration:none;
    color:inherit;
    transition:all 0.3s ease;
    height:540px;
    display:flex;
    flex-direction:column;
}

.subcategory-card:hover{
    box-shadow:0 20px 40px rgba(0,0,0,0.15);
    transform:translateY(-26px) rotateX(7deg);
}

.subcategory-card-image-wrapper{
    position:relative;
    overflow:hidden;
    flex:1;
}

.subcategory-card-image{
    width:100%;
    height:100%;
    object-fit:cover;
    transition:transform 0.4s ease;
    transform:scale(1.12) translateY(-8px);
}

.subcategory-card:hover .subcategory-card-image{
    transform:scale(1.28) translateY(-22px);
}

.subcategory-card-overlay{
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.3) 100%);
    pointer-events:none;
}

.subcategory-card-text{
    padding:2rem;
    font-size:0.95rem;
    line-height:1.6;
    color:var(--black);
}

.subcategory-card-name{
    display:none;
}

@media (max-width:1024px){
    .subcategory-cards-grid{
        grid-template-columns:repeat(2,1fr);
        gap:2rem;
    }
}

@media (max-width:768px){
    .subcategory-group{
        margin:4rem 0 5rem;
        padding:0 1rem;
    }
    .subcategory-cards-grid{
        grid-template-columns:1fr;
        gap:1.5rem;
    }
    .subcategory-group-title{
        font-size:2rem;
    }
}
</style>
@endpush

@section('content')
<!-- Premium Banner Carousel -->
@include('components.banner-carousel', ['banners' => $banners])

<div class="home-container">
    <!-- Wallets Section -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Wallets</h2>
        <div class="subcategory-cards-grid">
            <a href="{{ route('products.index', ['categories' => ['Wallets']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/accessories/wallets-1.jpg') }}" alt="Wallets" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Premium Leather Crafted with Timeless Elegance and Sophistication</div>
                <div class="subcategory-card-name">Wallets</div>
            </a>
            <a href="{{ route('products.index', ['categories' => ['Wallets']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/accessories/wallets-2.jpg') }}" alt="Wallets" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Exquisite Designs Define Luxury Wallets Perfect for Every Occasion</div>
                <div class="subcategory-card-name">Wallets</div>
            </a>
            <a href="{{ route('products.index', ['categories' => ['Wallets']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/accessories/wallets-3.jpg') }}" alt="Wallets" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Functional Style Meets Premium Materials in Our Wallet Collection</div>
                <div class="subcategory-card-name">Wallets</div>
            </a>
        </div>
    </div>

    <!-- Belts Section -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Belts</h2>
        <div class="subcategory-cards-grid">
            <a href="{{ route('products.index', ['categories' => ['Belts']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/accessories/belts-1.jpg') }}" alt="Belts" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Refined Styling Defines Your Silhouette with Perfect Precision Fit</div>
                <div class="subcategory-card-name">Belts</div>
            </a>
            <a href="{{ route('products.index', ['categories' => ['Belts']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/accessories/belts-2.jpg') }}" alt="Belts" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Elevated Accessories Complement Your Wardrobe with Timeless Class</div>
                <div class="subcategory-card-name">Belts</div>
            </a>
            <a href="{{ route('products.index', ['categories' => ['Belts']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/accessories/belts-3.jpg') }}" alt="Belts" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Designer Belts Transform Any Outfit Into Sophisticated Elegance</div>
                <div class="subcategory-card-name">Belts</div>
            </a>
        </div>
    </div>

    <!-- Watches Section -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Watches</h2>
        <div class="subcategory-cards-grid">
            <a href="{{ route('products.index', ['categories' => ['Watches']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/accessories/watches-leather-1.jpg') }}" alt="Watches" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Leather Strap Watches Combine Timeless Design with Modern Precision</div>
                <div class="subcategory-card-name">Watches - Leather Strap</div>
            </a>
            <a href="{{ route('products.index', ['categories' => ['Watches']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/accessories/watches-chain-1.jpg') }}" alt="Watches" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Chain Strap Watches Exude Luxury and Contemporary Sophisticated Style</div>
                <div class="subcategory-card-name">Watches - Chain Strap</div>
            </a>
            <a href="{{ route('products.index', ['categories' => ['Watches']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/accessories/watches-2.jpg') }}" alt="Watches" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Premium Timepieces Elevate Any Ensemble with Refined Personal Style</div>
                <div class="subcategory-card-name">Watches</div>
            </a>
        </div>
    </div>

    <!-- Sunglasses Section -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Sunglasses</h2>
        <div class="subcategory-cards-grid">
            <a href="{{ route('products.index', ['categories' => ['Sunglasses']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/accessories/sunglasses-1.jpg') }}" alt="Sunglasses" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Iconic Sunglasses Define Your Style with Timeless UV Protection</div>
                <div class="subcategory-card-name">Sunglasses</div>
            </a>
            <a href="{{ route('products.index', ['categories' => ['Sunglasses']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/accessories/sunglasses-2.jpg') }}" alt="Sunglasses" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Designer Eyewear Combines Fashion with Premium Quality Craftsmanship</div>
                <div class="subcategory-card-name">Sunglasses</div>
            </a>
            <a href="{{ route('products.index', ['categories' => ['Sunglasses']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/accessories/sunglasses-3.jpg') }}" alt="Sunglasses" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Trendy Styles Protect Your Eyes While Enhancing Your Appearance</div>
                <div class="subcategory-card-name">Sunglasses</div>
            </a>
        </div>
    </div>

    <!-- Caps Section -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Caps</h2>
        <div class="subcategory-cards-grid">
            <a href="{{ route('products.index', ['categories' => ['Caps']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/accessories/caps-1.jpg') }}" alt="Caps" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Classic Caps Add Casual Flair to Your Everyday Fashion Ensemble</div>
                <div class="subcategory-card-name">Caps</div>
            </a>
            <a href="{{ route('products.index', ['categories' => ['Caps']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/accessories/caps-2.jpg') }}" alt="Caps" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Stylish Accessories Perfect for Sports or Casual Weekend Wear</div>
                <div class="subcategory-card-name">Caps</div>
            </a>
            <a href="{{ route('products.index', ['categories' => ['Caps']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/accessories/caps-3.jpg') }}" alt="Caps" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Premium Caps Combine Comfort with Contemporary Urban Style</div>
                <div class="subcategory-card-name">Caps</div>
            </a>
        </div>
    </div>
</div>

@endsection
