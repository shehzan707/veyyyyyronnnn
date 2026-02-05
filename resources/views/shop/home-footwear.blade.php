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
.poster-group{
    margin:6rem 0 8rem;
    padding:0 2.2rem;
}

/* TITLES */
.poster-group-title{
    font-size:3.2rem;
    font-weight:900;
    letter-spacing:5px;
    text-transform:uppercase;
    margin-bottom:4.8rem;
    position:relative;
    color:#000;
}

/* GHOST BRAND */
.poster-group-title::before{
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
.poster-group-title::after{
    content:'';
    position:absolute;
    left:0;
    bottom:-18px;
    width:90px;
    height:3px;
    background:#000;
}

/* GRID */
.poster-cards-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
    gap:3.4rem;
}

/* CARD – EDGY */
.poster-card{
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

.poster-card:hover{
    transform:translateY(-26px) rotateX(7deg);
    box-shadow:0 90px 140px rgba(0,0,0,.55);
}

/* IMAGE */
.poster-card-image{
    position:absolute;
    inset:0;
    overflow:hidden;
}

.poster-card-image img{
    width:100%;
    height:100%;
    object-fit:cover;
    transform:scale(1.08);
    transition:transform 1.2s cubic-bezier(.23,1,.32,1),
               filter .8s ease;
}

.poster-card:hover img{
    transform:scale(1.28) translateY(-22px);
    filter:grayscale(.05) contrast(1.1);
}

/* INDUSTRIAL MASK */
.poster-card::after{
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

.poster-card:hover::after{
    opacity:1;
}

/* CONTENT */
.poster-card-content{
    position:absolute;
    bottom:0;
    width:100%;
    padding:3.2rem 2.6rem;
    transform:translateY(46px);
    transition:transform .8s cubic-bezier(.23,1,.32,1);
    z-index:2;
}

.poster-card:hover .poster-card-content{
    transform:translateY(0);
}

/* NAME */
.poster-card-name{
    font-size:1.9rem;
    font-weight:900;
    letter-spacing:1.6px;
    margin:0 0 .8rem;
}

/* TAGLINE */
.poster-card-tagline{
    font-size:.95rem;
    color:#d4d4d4;
    letter-spacing:.8px;
    line-height:1.6;
    text-transform:uppercase;
}

/* HARD LINE ACCENT */
.poster-card-content::before{
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

.poster-card:hover .poster-card-content::before{
    transform:scaleX(1);
}

/* RESPONSIVE */
@media(max-width:900px){
    .poster-group-title{font-size:2.4rem}
    .poster-group-title::before{font-size:5.2rem}
    .poster-card{height:440px}
}

@media(max-width:600px){
    .poster-group{padding:0 1.4rem}
    .poster-cards-grid{gap:2.2rem}
}
</style>
@endpush

@section('content')

@include('components.banner-carousel', [
    'banners' => $banners,
    'filterUrl' => 'http://127.0.0.1:8000/products?gender=footwear'
])

<div class="home-container">
    <div class="posters-section">

        <!-- MEN -->
        <div class="poster-group">
            <div class="veyron-mark">VEYRON</div>
            <h2 class="poster-group-title">Men’s Footwear</h2>

            <div class="poster-cards-grid">

                <a href="{{ route('products.index', ['categories[]' => 'Sneakers']) }}" class="poster-card">
                    <div class="poster-card-image">
                        <img src="{{ asset('images/l1.jpg') }}">
                    </div>
                    <div class="poster-card-content">
                       
                        <p class="poster-card-tagline">
                            Luxury that walks softly, yet speaks volumes
                        </p>
                    </div>
                </a>

                <a href="{{ route('products.index', ['categories[]' => 'Casual Shoes']) }}" class="poster-card">
                    <div class="poster-card-image">
                        <img src="{{ asset('images/sneak.jpg') }}">
                    </div>
                    <div class="poster-card-content">
                        
                        <p class="poster-card-tagline">
                            Refined casuals engineered for movement.
                        </p>
                    </div>
                </a>

                <a href="{{ route('products.index', ['categories[]' => 'Sneakers']) }}" class="poster-card">
                    <div class="poster-card-image">
                        <img src="{{ asset('images/l2.jpg') }}">
                    </div>
                    <div class="poster-card-content">
               
                        <p class="poster-card-tagline">
                            Street-dominant sneakers with couture restraint.
                        </p>
                    </div>
                </a>

                <a href="{{ route('products.index', ['categories[]' => 'Casual Shoes']) }}" class="poster-card">
                    <div class="poster-card-image">
                        <img src="{{ asset('images/slides.jpg') }}">
                    </div>
                    <div class="poster-card-content">
                      
                        <p class="poster-card-tagline">
                            Performance silhouettes with disciplined design.
                        </p>
                    </div>
                </a>

            </div>
        </div>

        <!-- WOMEN -->
        <div class="poster-group">
            <div class="veyron-mark">VEYRON</div>
            <h2 class="poster-group-title">Women’s Footwear</h2>

            <div class="poster-cards-grid">

                <a href="{{ route('products.index', ['categories[]' => 'casual boots']) }}" class="poster-card">
                    <div class="poster-card-image">
                        <img src="{{ asset('images/w5.png') }}">
                    </div>
                    <div class="poster-card-content">
                       
                        <p class="poster-card-tagline">
                           Contemporary forms with timeless intent.
                        </p>
                    </div>
                </a>

                <a href="{{ route('products.index', ['categories[]' => 'casual boots']) }}" class="poster-card">
                    <div class="poster-card-image">
                        <img src="{{ asset('images/w3.jpg') }}">
                    </div>
                    <div class="poster-card-content">
                       
                        <p class="poster-card-tagline">
                           Understated silhouettes for elevated Daily wear.
                        </p>
                    </div>
                </a>

                <a href="{{ route('products.index', ['categories[]' => 'Sneaker']) }}" class="poster-card">
                    <div class="poster-card-image">
                        <img src="{{ asset('images/w4.jpg') }}">
                    </div>
                    <div class="poster-card-content">
                     
                        <p class="poster-card-tagline">
                            Effortless sneakers with elevated attitude.
                        </p>
                    </div>
                </a>

                <a href="{{ route('products.index', ['categories[]' => 'Sneaker']) }}" class="poster-card">
                    <div class="poster-card-image">
                        <img src="{{ asset('images/w1.jpeg') }}">
                    </div>
                    <div class="poster-card-content">
                        
                        <p class="poster-card-tagline">
                            Warm-season silhouettes with clean dominance.
                        </p>
                    </div>
                </a>

            </div>
        </div>

    </div>
</div>

@endsection
