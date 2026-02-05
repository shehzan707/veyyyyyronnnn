@extends('layouts.app')

@section('title', 'VEYRON — Women\'s Collection')

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
    <!-- Topwear Section -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Topwear</h2>
        <div class="subcategory-cards-grid">
            <a href="{{ route('products.index', ['gender' => 'women', 'categories' => ['Tops']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/women/topwear-1.jpg') }}" alt="Women's Tops" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Refined Elegance with Understated Sophistication Here Today</div>
                <div class="subcategory-card-name">Topwear</div>
            </a>
            <a href="{{ route('products.index', ['gender' => 'women', 'categories' => ['T-Shirts']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/women/topwear-crop-1.jpg') }}" alt="Women's Crop T-Shirts" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Contemporary Design Meets Timeless Classical Grace</div>
                <div class="subcategory-card-name">Crop T-Shirts</div>
            </a>
            <a href="{{ route('products.index', ['gender' => 'women', 'categories' => ['Tops']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/women/topwear-casual-1.jpg') }}" alt="Women's Casual Tops" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Effortless Style Embodied in Premium Comfort Today</div>
                <div class="subcategory-card-name">Casual Tops</div>
            </a>
        </div>
    </div>

    <!-- Bottomwear Section -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Bottomwear</h2>
        <div class="subcategory-cards-grid">
            <a href="{{ route('products.index', ['gender' => 'women', 'categories' => ['Jeans']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/women/bottomwear-jeans-1.jpg') }}" alt="Women's Jeans" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Legendary Denim Refined with Timeless Elegant Essence</div>
                <div class="subcategory-card-name">Jeans</div>
            </a>
            <a href="{{ route('products.index', ['gender' => 'women', 'categories' => ['Leggings']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/women/bottomwear-leggings-1.jpg') }}" alt="Women's Leggings" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Comfortable Elegance Designed for Active Contemporary Life</div>
                <div class="subcategory-card-name">Leggings</div>
            </a>
            <a href="{{ route('products.index', ['gender' => 'women', 'categories' => ['Skirts']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/women/bottomwear-skirts-1.jpg') }}" alt="Women's Skirts" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Elegant Doll Skirt Flow in Timeless Classical Design</div>
                <div class="subcategory-card-name">Skirts</div>
            </a>
        </div>
    </div>

    <!-- Dresses Section -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Dresses</h2>
        <div class="subcategory-cards-grid">
            <a href="{{ route('products.index', ['gender' => 'women', 'categories' => ['Dresses']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/women/dresses-casual-1.jpg') }}" alt="Women's Casual Dresses" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Casual Elegance Brings Timeless Beauty to Day Wear</div>
                <div class="subcategory-card-name">Casual Dresses</div>
            </a>
            <a href="{{ route('products.index', ['gender' => 'women', 'categories' => ['Dresses']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/women/dresses-formal-1.jpg') }}" alt="Women's Formal Dresses" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Formal Sophistication Defines Timeless Elegant Luxury</div>
                <div class="subcategory-card-name">Formal Dresses</div>
            </a>
            <a href="{{ route('products.index', ['gender' => 'women', 'categories' => ['Dresses']]) }}" class="subcategory-card">
                <div class="subcategory-card-image-wrapper">
                    <img src="{{ asset('images/women/dresses-party-1.jpg') }}" alt="Women's Party Dresses" class="subcategory-card-image">
                    <div class="subcategory-card-overlay"></div>
                </div>
                <div class="subcategory-card-text">Party Perfect Designs Create Unforgettable Elegant Moments</div>
                <div class="subcategory-card-name">Party Dresses</div>
            </a>
        </div>
    </div>
</div>

@endsection
    position:relative;
    height:540px;
    background:#000;
    border-radius:6px;
    overflow:hidden;
    text-decoration:none;
    color:#fff;
    transform-style:preserve-3d;
    transition:
        transform .9s cubic-bezier(.23,1,.32,1),
        box-shadow .9s cubic-bezier(.23,1,.32,1);
    box-shadow:0 30px 60px rgba(0,0,0,.35);
}

.subcategory-card:hover{
    transform:translateY(-26px) rotateX(7deg);
    box-shadow:0 90px 140px rgba(0,0,0,.55);
}

/* IMAGE */
.subcategory-card-image{
    position:absolute;
    inset:0;
    overflow:hidden;
}

.subcategory-card-image img{
    width:100%;
    height:100%;
    object-fit:cover;
    transform:scale(1.08);
    transition:transform 1.2s cubic-bezier(.23,1,.32,1),
               filter .8s ease;
}

.subcategory-card:hover img{
    transform:scale(1.28) translateY(-22px);
    filter:grayscale(.05) contrast(1.1);
}

/* MASK */
.subcategory-card::after{
    content:'';
    position:absolute;
    inset:0;
    background:
        linear-gradient(
            to top,
            rgba(0,0,0,.92),
            rgba(0,0,0,.35),
            rgba(0,0,0,.95)
        );
    opacity:.85;
    transition:opacity .6s ease;
}

.subcategory-card:hover::after{
    opacity:1;
}

/* CONTENT */
.subcategory-card-content{
    position:absolute;
    bottom:0;
    width:100%;
    padding:3.2rem 2.6rem;
    transform:translateY(46px);
    transition:transform .8s cubic-bezier(.23,1,.32,1);
    z-index:2;
}

.subcategory-card:hover .subcategory-card-content{
    transform:translateY(0);
}

/* NAME */
.subcategory-card-name{
    font-size:1.9rem;
    font-weight:900;
    letter-spacing:1.6px;
    margin:0 0 .8rem;
}

/* TAGLINE */
.subcategory-card-tagline{
    font-size:.95rem;
    color:#d4d4d4;
    letter-spacing:.8px;
    line-height:1.6;
    text-transform:uppercase;
}

/* ACCENT LINE */
.subcategory-card-content::before{
    content:'';
    position:absolute;
    top:-22px;
    left:2.6rem;
    width:72px;
    height:3px;
    background:#fff;
    transform:scaleX(0);
    transform-origin:left;
    transition:transform .8s cubic-bezier(.23,1,.32,1);
}

.subcategory-card:hover .subcategory-card-content::before{
    transform:scaleX(1);
}

/* RESPONSIVE */
@media(max-width:900px){
    .subcategory-group-title{font-size:2.4rem}
    .subcategory-group-title::before{font-size:5.2rem}
    .subcategory-card{height:440px}
}

@media(max-width:600px){
    .subcategory-group{padding:0 1.4rem}
    .subcategory-cards-grid{gap:2.2rem}
}
</style>
@endpush

@section('content')

@include('components.banner-carousel', [
    'banners' => $banners,
    'filterUrl' => 'http://127.0.0.1:8000/products?gender=women'
])

<div class="home-container">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-size: 3.5rem; font-weight: 900; color: #DB7093; margin: 2rem 0 1rem 0; text-transform: uppercase; letter-spacing: 3px; font-family: 'Georgia', 'Poster', serif;">Curated Womenswear</h1>
    </div>

    <!-- TOPWEAR -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Defined Femininity</h2>
        <div class="subcategory-cards-grid">

            @foreach([
                ['tops','Tops','Modern lines. Soft authority.','tops.jpg'],
                ['Crop tops','Crop T-Shirts','Minimal cut. Maximum impact.','croptop.jpeg'],
                ['shirts','Shirts','Structure with elegance','shirts.jpg'],
                ['sweatshirts','Sweaters','Warm layers. Sharp presence.','sweater.jpg'],
            ] as [$cat,$name,$tag,$img])
            <a href="{{ route('products.index',['category'=>$cat,'gender'=>'women']) }}" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/'.$img) }}" alt="{{ $name }}">
                </div>
                <div class="subcategory-card-content">
                    <h3 class="subcategory-card-name">{{ $name }}</h3>
                    <p class="subcategory-card-tagline">{{ $tag }}</p>
                </div>
            </a>
            @endforeach

        </div>
    </div>

    <!-- BOTTOMWEAR -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Sculpted Movement</h2>
        <div class="subcategory-cards-grid">

            @foreach([
                ['jeans','Jeans','Timeless fit. Modern edge.','jeans.jpg'],
                ['SweatPants','Sweat Pants','Comfort, disciplined','swt.jpg'],
                ['Half Skirts','Half Skirts','Precision with grace','skirt.jpg'],
                ['Long skirts','Long Skirts','Fluid motion. Clean form.','lskirt.jpg'],
            ] as [$cat,$name,$tag,$img])
            <a href="{{ route('products.index',['category'=>$cat,'gender'=>'women']) }}" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/'.$img) }}" alt="{{ $name }}">
                </div>
                <div class="subcategory-card-content">
                    <h3 class="subcategory-card-name">{{ $name }}</h3>
                    <p class="subcategory-card-tagline">{{ $tag }}</p>
                </div>
            </a>
            @endforeach

        </div>
    </div>

</div>
@endsection
