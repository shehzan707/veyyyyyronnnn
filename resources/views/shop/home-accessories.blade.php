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
    --card-width:100%;
    --card-height:auto;
    --card-aspect-ratio:1 / 1;
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
    padding:0 1.8rem;
    position:relative;
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
    display:flex;
    align-items:center;
    gap:2rem;
    z-index:1;
}

.subcategory-group-title::after{
    flex:1;
    height:2px;
    background:#000;
    content:'';
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

/* GRID - 2 columns for accessories */
.subcategory-cards-grid{
    display:grid;
    grid-template-columns:repeat(2, 1fr);
    gap:2.4rem;
}

/* CARD – EDGY */
.subcategory-card{
    position:relative;
    width:var(--card-width);
    height:var(--card-height);
    aspect-ratio:var(--card-aspect-ratio);
    max-width:75%;
    margin:0 auto;
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

/* CARD SIZE MODIFIERS */
.subcategory-card.small{
    --card-width:100%;
    --card-height:auto;
    --card-aspect-ratio:1.2 / 1;
}

.subcategory-card.medium{
    --card-width:100%;
    --card-height:auto;
    --card-aspect-ratio:1 / 1;
}

.subcategory-card.large{
    --card-width:100%;
    --card-height:auto;
    --card-aspect-ratio:0.8 / 1;
}

.subcategory-card.fixed-300{
    width:50px;
    height:50px;
    aspect-ratio:unset;
}

.subcategory-card.fixed-350{
    width:2000px;
    height:200px;
    aspect-ratio:unset;
}

.subcategory-card.fixed-400{
    width:300px;
    height:300px;
    aspect-ratio:unset;
}

/* RESPONSIVE */
@media(max-width:600px){
    .subcategory-group-title{font-size:2.4rem; gap:1.5rem;}
    .subcategory-group-title::before{font-size:5.2rem}
    .subcategory-cards-grid{gap:2.0rem;}
}

@media(max-width:600px){
    .subcategory-group{padding:0 1.2rem}
    .subcategory-cards-grid{gap:1.6rem; grid-template-columns:1fr;}
    .subcategory-group-title{font-size:1.8rem; gap:1rem;}
    .subcategory-group-title::before{font-size:3.5rem}
}
</style>
@endpush

@section('content')

@include('components.banner-carousel', [
    'banners' => $banners,
    'section' => 'accessories'
])

<div class="home-container">

    <!-- GROUP 1: SUNGLASSES -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">STYLE NEEDS</h2>
        <div class="subcategory-cards-grid">
            <a href="{{ url('products') }}?gender=accessories&categories[]=Sunglasses&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/s01.jpg') }}" alt="Sunglasses">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Elevate your vision with remarkable timeless luxury that redefines contemporary style.</p>
                </div>
            </a>

            <a href="{{ url('products') }}?gender=accessories&categories[]=Caps&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/c01.jpg') }}" alt="Sunglasses">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Crown yourself with premium classic luxury style that perfectly reflects timeless elegance always.</p>
                </div>
            </a>
        </div>
    </div>

    <!-- GROUP 2: CAPS -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Essential WATCHES</h2>
        <div class="subcategory-cards-grid">
            <a href="{{ url('products') }}?gender=accessories&categories[]=Leather+Straps&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/wa01.jpg') }}" alt="Caps">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Refined craftsmanship in leather designed for timeless wrist elegance that defines pure luxury.</p>
                </div>
            </a>

            <a href="{{ url('products') }}?gender=accessories&categories[]=Metal+Straps&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/wa02.jpg') }}" alt="Caps">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Sophisticated metal precision perfectly meets classic luxury standards embodying timeless.</p>
                </div>
            </a>
        </div>
    </div>

    <!-- GROUP 3: WATCHES -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">CURATED BAGS</h2>
        <div class="subcategory-cards-grid">
            <a href="{{ url('products') }}?gender=accessories&categories[]=Backpacks&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/b01.jpg') }}" alt="Watches">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Carry pure elegance and luxury crafted with innovative design bringing timeless sophistication.</p>
                </div>
            </a>

            <a href="{{ url('products') }}?gender=accessories&categories[]=Handbags&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/b02.jpg') }}" alt="Watches">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">The ultimate luxury statement piece for refined women who deeply appreciate timeless elegance.</p>
                </div>
            </a>
        </div>
    </div>

    <!-- GROUP 4: LEATHER STRAP -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">LEATHER GOODS</h2>
        <div class="subcategory-cards-grid">
            <a href="{{ url('products') }}?gender=accessories&categories[]=Belts&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/be01.jpg') }}" alt="Leather Strap">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Cinch your style with classic luxury leather that perfectly defines timeless elegance refined.</p>
                </div>
            </a>

            <a href="{{ url('products') }}?gender=accessories&categories[]=Wallets&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/wal01.jpg') }}" alt="Leather Strap">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Perfect blend of luxury and timeless classic elegance representing our commitment.</p>
                </div>
            </a>
        </div>
    </div>

    <!-- GROUP 5: METAL STRAP -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">ESSENTIALS OF HAND</h2>
        <div class="subcategory-cards-grid">
            <a href="{{ url('products') }}?gender=accessories&categories[]=Bracelets&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/ba01.jpg') }}" alt="Metal Strap">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Adorn your wrist with refined luxury and timeless grace that speaks to your sophistication.</p>
                </div>
            </a>

            <a href="{{ url('products') }}?gender=accessories&categories[]=Rings&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/r01.jpg') }}" alt="Metal Strap">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Timeless classic perfection for modern luxury refined style celebrating timeless elegance.</p>
                </div>
            </a>
        </div>
    </div>

</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
@endsection
