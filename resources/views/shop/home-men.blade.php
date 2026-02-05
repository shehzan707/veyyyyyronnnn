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
    grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
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

@include('components.banner-carousel', [
    'banners' => $banners,
    'filterUrl' => 'http://127.0.0.1:8000/products?gender=men'
])

<div class="home-container">

    <!-- TOPWEAR -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Foundations of Authority</h2>
        <div class="subcategory-cards-grid">

            @foreach([
                ['tshirt','T-Shirts','Essential silhouettes crafted for modern comfort.','tshirt.jpg'],
                ['casual-shirts','Casual Shirts','Casual forms elevated through subtle craftsmanship.','beige.jpeg'],
                ['formal-shirts','Formal Shirts','Refined shirts designed for commanding elegance.','formalshirt.jpg'],
                ['sweatshirts','Knitwear','Cold weather. Warm intent.','sweatshirts.jpeg'],
                ['jackets','Jackets','Functional design refined through luxury detailing.','jackets.jpg'],
                ['blazers','Blazers','The Precision-cut blazers for elevated presence.','blazers.jpg'],
                ['suits','Suits','Power dressing defined by refined craftsmanship.','suits.jpg'],
                ['overcoats','Overcoats','Statement layers crafted for enduring elegance.','overcoats.jpg'],
            ] as [$cat,$name,$tag,$img])
            <a href="{{ route('products.index',['category'=>$cat,'gender'=>'men']) }}" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/'.$img) }}" alt="{{ $name }}">
                </div>
                <div class="subcategory-card-content">
                    
                    <p class="subcategory-card-tagline">{{ $tag }}</p>
                </div>
            </a>
            @endforeach

        </div>
    </div>

    <!-- BOTTOMWEAR -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Engineered Silhouettes</h2>
        <div class="subcategory-cards-grid">

            @foreach([
                ['jeans','Jeans','Timeless jeans designed for everyday authority.','jeans.jpeg'],
                ['trousers','Trousers','Tailored trousers shaped for contemporary elegance.','trousers.jpg'],
                ['shorts','Shorts','Refined proportions designed for modern leisure','shorts.jpg'],
                ['track-pants','Track Pants','Relaxed tailoring redefined for luxury comfort.','trackpants.jpg'],
            ] as [$cat,$name,$tag,$img])
            <a href="{{ route('products.index',['category'=>$cat,'gender'=>'men']) }}" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="{{ asset('images/'.$img) }}" alt="{{ $name }}">
                </div>
                <div class="subcategory-card-content">
                    
                    <p class="subcategory-card-tagline">{{ $tag }}</p>
                </div>
            </a>
            @endforeach

        </div>
    </div>

</div>
@endsection
