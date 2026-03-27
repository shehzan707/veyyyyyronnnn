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
    color:rgba(0,0,0,0.12);
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
    grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
    gap:3.4rem;
}

/* CARD – EDGY */
.subcategory-card{
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
    transform:scale(1.02);
    filter:grayscale(.05) contrast(1.1);
}

/* MASK */
/* Black shade overlay removed */

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
    font-size:1.15rem;
    color:#666;
    letter-spacing:.8px;
    line-height:1.8;
    text-transform:capitalize;
    transition:color .7s ease, transform .7s ease;
}

.subcategory-card:hover .subcategory-card-tagline{
    color:#000;
    transform:translateY(-2px);
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
    display:none;
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
    'section' => 'women'
])

<div class="home-container">

    <!-- TOPWEAR -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Defined Femininity</h2>
        <div class="subcategory-cards-grid">

            <!-- TOPS -->
            <a href="{{ url('products') }}?gender=women&categories[]=Crop+Tops&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/croptop.jpeg') }}" alt="Tops">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Modern sophistication shaped in every cut</p>
                </div>
            </a>

            <!-- CROP TOPS -->
            <a href="{{ url('products') }}?gender=women&categories[]=Shirts&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/shirt.jpeg') }}" alt="Crop T-Shirts">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Bold and minimal with confident presence</p>
                </div>
            </a>

            <!-- SHIRTS -->
            <a href="{{ url('products') }}?gender=women&categories[]=Sweaters&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/sweatshirtt.jpeg') }}" alt="Shirts">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Structured elegance in pure feminine form</p>
                </div>
            </a>

                <!-- SWEATSHIRTS -->
                <a href="{{ url('products') }}?gender=women&categories[]=Dresses&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/dress.jpg') }}" alt="Sweaters">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Sophisticated warmth meets effortless grace</p>
                </div>
            </a>

        </div>
    </div>

    <!-- BOTTOMWEAR -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Sculpted Movement</h2>
        <div class="subcategory-cards-grid">

            <!-- JEANS -->
            <a href="{{ url('products') }}?gender=women&categories[]=Jeans&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/jeans.jpeg') }}" alt="Jeans">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Timeless cut redefined for modern women</p>
                </div>
            </a>

            <!-- SWEAT PANTS -->
            <a href="{{ url('products') }}?gender=women&categories[]=Sweat+bottoms&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/sweatbottoms.jpg') }}" alt="Sweat Pants">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Elevated comfort merged with refined style</p>
                </div>
            </a>

            <!-- HALF SKIRTS -->
            <a href="{{ url('products') }}?gender=women&categories[]=Half+Skirts&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/halfskirt.jpeg') }}" alt="Half Skirts">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Precision tailoring dressed in graceful elegance</p>
                </div>
            </a>

            <!-- LONG SKIRTS -->
            <a href="{{ url('products') }}?gender=women&categories[]=Long+Skirts&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/longskirt.jpg') }}" alt="Long Skirts">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Fluid elegance captured in sophisticated motion</p>
                </div>
            </a>

        </div>
    </div>

</div>
@endsection
