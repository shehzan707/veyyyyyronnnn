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

/* RESET */
*{box-sizing:border-box}
body{
    margin:0;
    font-family:'Poppins',system-ui,sans-serif;
    background:var(--white);
    color:var(--black);
}

/* FULL BLEED CONTAINER */
.home-container{
    max-width:100%;
    margin:0;
    padding:2px;
}

/* GROUP */
.subcategory-group{
    margin:6rem 0 8rem;
    padding:0 2.2rem;
}

/* TITLES */
.subcategory-group-title{
    font-size:3.2rem;
    font-weight:900;
    letter-spacing:5px;
    text-transform:uppercase;
    margin-bottom:4.8rem;
    position:relative;
    color:#000;
}

/* GHOST BRAND */
.subcategory-group-title::before{
    content:'VEYRON';
    position:absolute;
    top:-2.4rem;
    left:0;
    font-size:7.2rem;
    font-weight:900;
    letter-spacing:10px;
    color:rgba(0,0,0,0.12); /* increased opacity */
    pointer-events:none;
}

/* HARD UNDERLINE */
.subcategory-group-title::after{
    content:'';
    position:absolute;
    left:0;
    bottom:-18px;
    width:90px;
    height:3px;
    background:#000;
}

/* GRID */
.subcategory-cards-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:3.4rem;
}

/* CARD – EDGY */
.subcategory-card{
    position:relative;
    height:540px;
    background:#000;
    border-radius:6px; /* sharp, edgy */
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

/* INDUSTRIAL MASK */
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
    display:none;
}

/* TAGLINE */
.subcategory-card-tagline{
    font-size:.95rem;
    color:#d4d4d4;
    letter-spacing:.8px;
    line-height:1.6;
    text-transform:uppercase;
}

/* HARD LINE ACCENT */
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
<!-- Premium Banner Carousel -->
@include('components.banner-carousel', ['banners' => $banners, 'filterUrl' => 'http://127.0.0.1:8000/products?gender=accessories'])

<!-- Subcategory Cards Section -->
<div class="home-container">
    <div class="subcategory-section">
        
        <!-- ACCESSORIES GROUP -->
        <div class="subcategory-group">
            <h2 class="subcategory-group-title">Finishing Grace</h2>
            <div class="subcategory-cards-grid">
                <a href="{{ route('products.index', ['category' => 'wallets']) }}" class="subcategory-card">
                    <div class="subcategory-card-image empty">
                        <img src="{{ asset('images/wallet.jpg') }}" alt="Wallets">
                    </div>
                    <div class="subcategory-card-content">
                        <h3 class="subcategory-card-name">Wallets</h3>
                        <p class="subcategory-card-tagline">Premium leather crafted with timeless elegance and sophistication</p>
                    </div>
                </a>

                <a href="{{ route('products.index', ['category' => 'belts']) }}" class="subcategory-card">
                    <div class="subcategory-card-image empty">
                        <img src="{{ asset('images/belt.jpg') }}" alt="Belts">
                    </div>
                    <div class="subcategory-card-content">
                        <h3 class="subcategory-card-name">Belts</h3>
                        <p class="subcategory-card-tagline">Refined styling defines your silhouette with perfect precision fit</p>
                    </div>
                </a>

                <a href="{{ route('products.index', ['category' => 'Leather Strap']) }}" class="subcategory-card">
                    <div class="subcategory-card-image empty">
                        <img src="{{ asset('images/watchesleatherstrap.jpg') }}" alt="Watches - Leather Strap">
                    </div>
                    <div class="subcategory-card-content">
                        <h3 class="subcategory-card-name">Watches - Leather Strap</h3>
                        <p class="subcategory-card-tagline">Where artisanal time-keeping meets refined classical wrist elegance</p>
                    </div>
                </a>

                  <a href="{{ route('products.index', ['category' => 'Chain Strap']) }}" class="subcategory-card">
                    <div class="subcategory-card-image empty">
                        <img src="{{ asset('images/watcheschainstrap.jpg') }}" alt="Watches - Chain Strap">
                    </div>
                    <div class="subcategory-card-content">
                        <h3 class="subcategory-card-name">Watches - Chain Strap</h3>
                        <p class="subcategory-card-tagline">Timeless elegance fused with luxury precision on your wrist</p>
                    </div>
                </a>

                <a href="{{ route('products.index', ['category' => 'sunglasses']) }}" class="subcategory-card">
                    <div class="subcategory-card-image empty">
                        <img src="{{ asset('images/sunglasses.jpg') }}" alt="Sunglasses">
                    </div>
                    <div class="subcategory-card-content">
                        <h3 class="subcategory-card-name">Sunglasses</h3>
                        <p class="subcategory-card-tagline">UV protection meets stylish sophisticated shades for every occasion</p>
                    </div>
                </a>

                <a href="{{ route('products.index', ['category' => 'Caps']) }}" class="subcategory-card">
                    <div class="subcategory-card-image empty">
                        <img src="{{ asset('images/capandhat.jpg') }}" alt="Caps & Hats">
                    </div>
                    <div class="subcategory-card-content">
                        <h3 class="subcategory-card-name">Caps & Hats</h3>
                        <p class="subcategory-card-tagline">Casual sporty sophistication with retro vintage inspired design flair</p>
                    </div>
                </a>

                

                <a href="{{ route('products.index', ['category' => 'rings']) }}" class="subcategory-card">
                    <div class="subcategory-card-image empty">
                        <img src="{{ asset('images/ring.jpg') }}" alt="Rings">
                    </div>
                    <div class="subcategory-card-content">
                        <h3 class="subcategory-card-name">Rings</h3>
                        <p class="subcategory-card-tagline">Statement pieces of elegant luminous shine and timeless grace</p>
                    </div>
                </a>

               

                <a href="{{ route('products.index', ['category' => 'backpacks']) }}" class="subcategory-card">
                    <div class="subcategory-card-image empty">
                        <img src="{{ asset('images/bagpack.jpg') }}" alt="Backpacks">
                    </div>
                    <div class="subcategory-card-content">
                        <h3 class="subcategory-card-name">Backpacks</h3>
                        <p class="subcategory-card-tagline">Meticulously crafted for motion and engineered to last forever</p>
                    </div>
                </a>

                <a href="{{ route('products.index', ['category' => 'handbags']) }}" class="subcategory-card">
                    <div class="subcategory-card-image empty">
                        <img src="{{ asset('images/handbag.jpg') }}" alt="Handbags">
                    </div>
                    <div class="subcategory-card-content">
                        <h3 class="subcategory-card-name">Handbags</h3>
                        <p class="subcategory-card-tagline">Functional refined carry designed as your essential luxury piece</p>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>
</div>


</div>
@endsection
