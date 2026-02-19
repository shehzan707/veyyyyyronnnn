@extends('layouts.app')

@section('title', 'VEYRON — Men\'s Collection')

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
    font-size:1.15rem;
    color:#d4d4d4;
    letter-spacing:.8px;
    line-height:1.8;
    text-transform:capitalize;
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
    'section' => 'men'
])

<div class="home-container">

    <!-- TOPWEAR -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Distinguished Elegance</h2>
        <div class="subcategory-cards-grid">

            <!-- Casual Shirts -->
            <a href="{{ url('products') }}?gender=men&categories[]=Casual+Shirts&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/casualshirt.jpg') }}" alt="casualshirt">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Power and precision in every stitch</p>
                </div>
            </a>

            <!-- Formal Shirts -->
            <a href="{{ url('products') }}?gender=men&categories[]=Formal+Shirts&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/formalshirt.jpg') }}" alt="Casual Shirts">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Refined ease meets best formal confidence</p>
                </div>
            </a>

            <!-- Blazer -->
            <a href="{{ url('products') }}?gender=men&categories[]=Blazer&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/blazer.jpg') }}" alt="T-Shirts">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Pure quality layered with style Essentials</p>
                </div>
            </a>

            <!--  -->
            <a href="{{ url('products') }}?gender=men&categories[]=Overcoats&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/over.jpg') }}" alt="Formal Shirts">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Structured perfection for the modern man</p>
                </div>
            </a>
          <!-- Suit -->
            <a href="{{ url('products') }}?gender=men&categories[]=Hoodie&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/hood.jpeg') }}" alt="Tops">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Power and precision in every stitch</p>
                </div>
            </a>

            <!-- Knitwear -->
            <a href="{{ url('products') }}?gender=men&categories[]=Knitwear&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/sweatshirt.jpeg') }}" alt="Casual Shirts">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Refined ease meets casual confidence</p>
                </div>
            </a>

            <!-- hoodie -->
            <a href="{{ url('products') }}?gender=men&categories[]=Jackets&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/jackets.jpg') }}" alt="T-Shirts">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Pure quality layered with style</p>
                </div>
            </a>

            <!-- jackets -->
            <a href="{{ url('products') }}?gender=men&categories[]=T+Shirt&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/t.jpg') }}" alt="Formal Shirts">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Structured perfection for the modern man</p>
                </div>
            </a>

        </div>
    </div>

    <!-- BOTTOMWEAR -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Sculpted Movement</h2>
        <div class="subcategory-cards-grid">

            <!-- JEANS -->
            <a href="{{ url('products') }}?gender=men&categories[]=Denim&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/denim.jpg') }}" alt="Jeans">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Timeless denim redefined with strength</p>
                </div>
            </a>

            <!-- TROUSERS -->
            <a href="{{ url('products') }}?gender=men&categories[]=Trousers&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/trouser.jpg') }}" alt="Trousers">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Clean lines crafted for modern men</p>
                </div>
            </a>

            <!-- SHORTS -->
            <a href="{{ url('products') }}?gender=men&categories[]=Shorts&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/shorts.jpg') }}" alt="Shorts">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Summer ready with immaculate Shorts</p>
                </div>
            </a>
<!-- ACTIVEWEAR -->
            
            <a href="{{ url('products') }}?gender=men&categories[]=Sweatpants&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/sweatp.jpg') }}" alt="Activewear">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Performance engineered for active lifestyle</p>
                </div>
            </a>

        </div>
    </div>

</div>
@endsection
