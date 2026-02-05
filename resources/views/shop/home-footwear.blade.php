@extends('layouts.app')

@section('title', 'VEYRON — Footwear')

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
    grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
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
    <!-- Men's Footwear Section -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Men's Footwear</h2>
        <div class="subcategory-cards-grid">
            <a href="{{ route('products.index', ['gender' => 'footwear', 'categories' => ['Casual Shoes']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/footwear/mens-casual-1.jpg') }}" alt="Men's Casual Shoes" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Casual Sneakers Perfect for Everyday Comfort and Effortless Style</div>
                <div class="subcategory-card-name">Men's Casual Shoes</div>
            </a>
            <a href="{{ route('products.index', ['gender' => 'footwear', 'categories' => ['Sports Shoes']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/footwear/mens-sports-1.jpg') }}" alt="Men's Sports Shoes" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Athletic Footwear Engineered for Performance and Active Lifestyles</div>
                <div class="subcategory-card-name">Men's Sports Shoes</div>
            </a>
            <a href="{{ route('products.index', ['gender' => 'footwear', 'categories' => ['Formal Shoes']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/footwear/mens-formal-1.jpg') }}" alt="Men's Formal Shoes" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Formal Shoes Deliver Timeless Elegance for Professional Occasions</div>
                <div class="subcategory-card-name">Men's Formal Shoes</div>
            </a>
        </div>
    </div>

    <!-- Women's Footwear Section -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Women's Footwear</h2>
        <div class="subcategory-cards-grid">
            <a href="{{ route('products.index', ['gender' => 'footwear', 'categories' => ['Casual Shoes']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/footwear/womens-casual-1.jpg') }}" alt="Women's Casual Shoes" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Casual Beauties Combine Comfort with Contemporary Chic Elegance</div>
                <div class="subcategory-card-name">Women's Casual Shoes</div>
            </a>
            <a href="{{ route('products.index', ['gender' => 'footwear', 'categories' => ['Heels']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/footwear/womens-heels-1.jpg') }}" alt="Women's Heels" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Elegant Heels Elevate Your Style with Refined Feminine Sophistication</div>
                <div class="subcategory-card-name">Women's Heels</div>
            </a>
            <a href="{{ route('products.index', ['gender' => 'footwear', 'categories' => ['Boots']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/footwear/womens-boots-1.jpg') }}" alt="Women's Boots" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Premium Boots Define Your Look with Timeless Luxurious Elegance</div>
                <div class="subcategory-card-name">Women's Boots</div>
            </a>
        </div>
    </div>
</div>

@endsection
