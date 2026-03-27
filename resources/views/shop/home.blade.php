@extends('layouts.app')

@section('title', 'VEYRON — Home')

@push('styles')
<style>
*{margin:0;padding:0;box-sizing:border-box}

body{
    font-family:'Poppins','Inter',sans-serif;
    background:#fff;
    color:#111;
}

/* FULLSCREEN VIDEO OVERLAY */
.fullscreen-video-container{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:#000;
    z-index:9999;
    opacity:1;
    animation:fadeInQuick 0.3s ease-out;
}

.fullscreen-video-container video{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
}

@keyframes fadeInQuick{
    from{
        opacity:0;
    }
    to{
        opacity:1;
    }
}

.home-container{
    max-width:1400px;
    margin:auto;
    padding:0 1rem;
}

/* SECTION */
.poster-category-section{
    margin:7rem 0;
    padding:4rem 0;
    border-top:1px solid #e8e8e8;
    position:relative;
}

.poster-category-section::before{
    content:'';
    position:absolute;
    top:0;
    left:50%;
    transform:translateX(-50%);
    width:60px;
    height:1px;
    background:linear-gradient(90deg,transparent,#333,transparent);
}

.poster-category-section:first-of-type{
    border-top:none;
}

.poster-category-section:first-of-type::before{
    display:none;
}

.category-header{
    text-align:center;
    margin-bottom:4rem;
}

.category-title{
    font-size:2.8rem;
    font-weight:700;
    letter-spacing:4px;
    text-transform:uppercase;
    color:#1a1a1a;
    margin-bottom:1rem;
    position:relative;
    display:inline-block;
    width:100%;
}

.category-title::after{
    content:'';
    position:absolute;
    bottom:-8px;
    left:50%;
    transform:translateX(-50%);
    width:80px;
    height:2px;
    background:#333;
}

.category-subtitle{
    font-size:0.9rem;
    color:#888;
    letter-spacing:2px;
    text-transform:uppercase;
    margin-top:2rem;
    font-weight:500;
}

/* GRID */
.poster-section{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:70px;
}

/* CARD */
.poster-card{
    text-decoration:none;
    color:inherit;
    display:flex;
    flex-direction:column;
    transition:all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    position:relative;
    margin:0;
}

/* IMAGE WRAPPER */
.poster-wrapper{
    position:relative;
    aspect-ratio:3/5;
    overflow:hidden;
    border-radius:0;
    background:#f5f5f5;
    box-shadow:0 8px 24px rgba(0,0,0,0.12);
    transition:transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94),box-shadow 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    transform:scaleX(1.15);
}

.poster-card:hover .poster-wrapper{
    transform:translateY(-3px) scaleX(1.15);
    box-shadow:0 20px 48px rgba(0,0,0,0.22);
}

/* IMAGE SWAP */
.poster-wrapper img{
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
    object-fit:cover;
    transition:opacity 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94),transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.poster-wrapper img.hover-img{
    opacity:0;
}

.poster-card:hover img.main-img{
    opacity:0;
    transform:scale(0.98);
}

.poster-card:hover img.hover-img{
    opacity:1;
    transform:scale(1.06);
}

/* OVERLAY */
.poster-overlay{
    position:absolute;
    inset:0;
    background:rgba(0,0,0,0.42);
    display:flex;
    align-items:center;
    justify-content:center;
    opacity:0;
    transition:opacity 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.poster-card:hover .poster-overlay{
    opacity:1;
}

.overlay-text{
    color:#fff;
    font-size:0.75rem;
    letter-spacing:3px;
    text-transform:uppercase;
    border:1.5px solid #fff;
    padding:0.8rem 1.8rem;
    font-weight:600;
    backdrop-filter:blur(2px);
}

/* WATERMARK */
.veyron-watermark{
    position:absolute;
    top:16px;
    right:16px;
    font-size:0.75rem;
    letter-spacing:2.5px;
    color:#fff;
    opacity:0.85;
    font-weight:600;
}

/* BRAND */
.poster-brand{
    text-align:center;
    padding:1.2rem 0.8rem;
    transition:all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.brand-name{
    font-size:1.45rem;
    font-weight:700;
    letter-spacing:1.5px;
    color:#1a1a1a;
    transition:all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    margin-bottom:0.6rem;
}

.brand-decoration{
    width:50px;
    height:1.5px;
    background:#333;
    margin:0.6rem auto;
    transition:all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    box-shadow:0 2px 4px rgba(0,0,0,0.1);
}

.poster-card:hover .brand-decoration{
    width:85px;
}

.brand-tagline{
    font-size:0.85rem;
    color:#777;
    letter-spacing:0.5px;
    font-weight:500;
    transition:all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    line-height:1.5;
}

.poster-card:hover .brand-tagline{
    color:#666;
    letter-spacing:1.5px;
}

/* RESPONSIVE */
@media(max-width:768px){
    .category-title{font-size:2rem}
}
</style>
@endpush

@section('content')
<!-- FULLSCREEN VIDEO 
<div class="fullscreen-video-container" id="videoContainer">
    <video id="startVideo" autoplay muted playsinline style="display:block;" preload="auto">
        <source src="{{ asset('images/start.mp4') }}" type="video/mp4">
        <source src="{{ asset('images/start.m4p') }}" type="video/mp4">
    </video>
</div>-->

@include('components.banner-carousel', ['banners' => $banners, 'section' => 'default'])











<!-- =========================================================
     HOT GRABS SECTION  —  paste anywhere in your HTML body
     ========================================================= -->
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
 
<style>
  .hg {
    background: #fff;
    padding: 28px 0 40px;
    font-family: 'Bebas Neue', sans-serif;
  }
 
  .hg__title {
    font-size: clamp(32px, 6vw, 50px);
    letter-spacing: .06em;
    color: #2a2a2a;
    text-align: center;
    margin: 0 0 30px;
    font-weight: 800;
    font-family: 'Montserrat', sans-serif;
    text-transform: uppercase;
    letter-spacing: 2px;
  }
 
  .hg__block { margin-bottom: 30px; }
 
  .hg__track {
    display: flex;
    flex-direction: row;
    gap: 10px;
    padding: 0 16px 6px;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    align-items: flex-start;
  }
  .hg__track::-webkit-scrollbar { display: none; }
 
  /* ── HERO CARD ── */
  .hg__hero {
    flex: 0 0 368px;
    height: 623px;
    border-radius: 18px;
    overflow: hidden;
    background: #fff;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    scroll-snap-align: start;
    transition: transform .18s;
    border: 3px solid #111;
    flex-shrink: 0;
  }
  .hg__hero:hover { transform: translateY(-3px); }
 
  .hg__hero-img {
    flex: 1;
    background: #e8e8e8;
    overflow: hidden;
  }
  .hg__hero-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
 
  .hg__hero-label {
    font-size: 24px;
    letter-spacing: .14em;
    color: #fff;
    background: #0d0d0d;
    text-align: center;
    padding: 25px 0;
    border-radius: 0 0 15px 15px;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }
 
  /* ── COLUMN PAIR ── */
  .hg__pair {
    display: flex;
    flex-direction: column;
    gap: 10px;
    flex-shrink: 0;
    scroll-snap-align: start;
    height: 623px;
  }
 
  /* ── CATEGORY CARD ── */
  .hg__card {
    width: 304px;
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    border: 3px solid #111;
    transition: transform .18s, border-color .18s;
    flex-shrink: 0;
  }
  .hg__card:hover {
    transform: translateY(-3px);
    border-color: #555;
  }
 
  .hg__card--short { height: 272px; }
  .hg__card--tall  { height: 336px; }
 
  .hg__card-img {
    flex: 1;
    background: #e8e8e8;
    overflow: hidden;
  }
  .hg__card-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
 
  /* ── BLACK label bar ── */
  .hg__card-label {
    font-size: 13px;
    letter-spacing: .15em;
    color: #fff;
    background: #0d0d0d;
    text-align: center;
    padding: 7px 4px 8px;
    border-top: 2px solid #111;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex-shrink: 0;
  }
</style>
 
<section class="hg">
 
  <h2 class="hg__title">Hot Grabs</h2>
 
  <!-- ══════════ MEN ══════════ -->
  <div class="hg__block">
    <div class="hg__track">
 
      <a class="hg__hero" href="{{ route('products.index', ['search' => 'men']) }}">
        <div class="hg__hero-img"><img id="menHeroImg" src="{{ asset('images/men1.jpeg') }}" alt="Men"></div>
        <div class="hg__hero-label">MEN</div>
      </a>
 
      <div class="hg__pair">
        <a class="hg__card hg__card--short" href="{{ route('products.index', ['search' => 'sneaker']) }}"><div class="hg__card-img"><img src="{{ asset('images/sneak.jpeg') }}" alt=""></div><div class="hg__card-label">SNEAKERS</div></a>
        <a class="hg__card hg__card--tall"  href="{{ route('products.index', ['search' => 'jeans']) }}">   <div class="hg__card-img"><img src="{{ asset('images/jeans.jpg') }}" alt=""></div><div class="hg__card-label">JEANS</div></a>
      </div>
 
      <div class="hg__pair">
        <a class="hg__card hg__card--tall"  href="{{ route('products.index', ['search' => 'watch']) }}">   <div class="hg__card-img"><img src="{{ asset('images/wat.jpeg') }}" alt=""></div><div class="hg__card-label">WATCHES</div></a>
        <a class="hg__card hg__card--short" href="{{ route('products.index', ['search' => 'shirt']) }}"><div class="hg__card-img"><img src="{{ asset('images/shirtt.jpg') }}" alt=""></div><div class="hg__card-label">SHIRTS</div></a>
      </div>
 
      <div class="hg__pair">
        <a class="hg__card hg__card--short" href="{{ route('products.index', ['search' => 'cap']) }}">    <div class="hg__card-img"><img src="{{ asset('images/cap.png') }}" alt=""></div><div class="hg__card-label">CAPS</div></a>
        <a class="hg__card hg__card--tall"  href="{{ route('products.index', ['search' => 'jacket']) }}"> <div class="hg__card-img"><img src="{{ asset('images/jacket.jpeg') }}" alt=""></div><div class="hg__card-label">JACKETS</div></a>
      </div>
 
      <div class="hg__pair">
        <a class="hg__card hg__card--tall"  href="{{ route('products.index', ['search' => 'hoodie']) }}">     <div class="hg__card-img"><img src="{{ asset('images/hoodie.jpg') }}" alt=""></div><div class="hg__card-label">HOODIES</div></a>
        <a class="hg__card hg__card--short" href="{{ route('products.index', ['search' => 'sunglass']) }}">  <div class="hg__card-img"><img src="{{ asset('images/glasses.jpg') }}" alt=""></div><div class="hg__card-label">SUNGLASSES</div></a>
      </div>
 
      <div class="hg__pair">
        <a class="hg__card hg__card--short" href="{{ route('products.index', ['search' => 't-shirt']) }}">      <div class="hg__card-img"><img src="{{ asset('images/te.jpeg') }}" alt=""></div><div class="hg__card-label">T-SHIRTS</div></a>
        <a class="hg__card hg__card--tall"  href="{{ route('products.index', ['search' => 'sweatshirt']) }}"> <div class="hg__card-img"><img src="{{ asset('images/sweat.jpeg') }}" alt=""></div><div class="hg__card-label">SWEATSHIRTS</div></a>
      </div>
 
      <div class="hg__pair">
        <a class="hg__card hg__card--tall"  href="{{ route('products.index', ['search' => 'jogger']) }}"><div class="hg__card-img"><img src="{{ asset('images/spant.png') }}" alt=""></div><div class="hg__card-label">JOGGERS</div></a>
        <a class="hg__card hg__card--short" href="{{ route('products.index', ['search' => 'OVERSIZED']) }}">  <div class="hg__card-img"><img src="{{ asset('images/oversize.jpg') }}" alt=""></div><div class="hg__card-label">OVERSIZED</div></a>
      </div>
 
    </div>
  </div>
 
  <!-- ══════════ WOMEN ══════════ -->
  <div class="hg__block">
    <div class="hg__track">
 
      <a class="hg__hero" href="{{ route('products.index', ['search' => 'women']) }}">
        <div class="hg__hero-img"><img id="womenHeroImg" src="{{ asset('images/wo1.jpeg') }}" alt="Women"></div>
        <div class="hg__hero-label">WOMEN</div>
      </a>
 
      <div class="hg__pair">
        <a class="hg__card hg__card--short" href="{{ route('products.index', ['search' => 'crop top']) }}">    <div class="hg__card-img"><img src="{{ asset('images/ct.jpeg') }}" alt=""></div><div class="hg__card-label">CROP TOPS</div></a>
        <a class="hg__card hg__card--tall"  href="{{ route('products.index', ['search' => 'jean']) }}"> <div class="hg__card-img"><img src="{{ asset('images/jean.jpeg') }}" alt=""></div><div class="hg__card-label">JEANS</div></a>
      </div>
 
      <div class="hg__pair">
        <a class="hg__card hg__card--tall"  href="{{ route('products.index', ['search' => 'sneaker']) }}">   <div class="hg__card-img"><img src="{{ asset('images/sneake.jpeg') }} " alt=""></div><div class="hg__card-label">SNEAKERS</div></a>
        <a class="hg__card hg__card--short" href="{{ route('products.index', ['search' => 'skirt']) }}"> <div class="hg__card-img"><img src="{{ asset('images/beh.jpeg') }}" alt=""></div><div class="hg__card-label">SKIRTS</div></a>
      </div>
 
      <div class="hg__pair">
        <a class="hg__card hg__card--short" href="{{ route('products.index', ['search' => 'SWEATSHIRT']) }}"><div class="hg__card-img"><img src="{{ asset('images/swe.jpeg') }}" alt=""></div><div class="hg__card-label">SWEATSHIRT</div></a>
        <a class="hg__card hg__card--tall"  href="{{ route('products.index', ['search' => 'long skirt']) }}">     <div class="hg__card-img"><img src="{{ asset('images/ls.jpg') }}" alt=""></div><div class="hg__card-label">LONG SKIRTS</div></a>
      </div>

      <div class="hg__pair">
        <a class="hg__card hg__card--tall"  href="{{ route('products.index', ['search' => 'bag']) }}"><div class="hg__card-img"><img src="{{ asset('images/bg.jpeg') }}" alt=""></div><div class="hg__card-label">BAGS</div></a>
        <a class="hg__card hg__card--short" href="{{ route('products.index', ['search' => 'sunglass']) }}"> <div class="hg__card-img"><img src="{{ asset('images/sun.jpeg') }}" alt=""></div><div class="hg__card-label">SUNGLASSES</div></a>
      </div>
 
      <div class="hg__pair">
        <a class="hg__card hg__card--short" href="{{ route('products.index', ['search' => 'cap']) }}">   <div class="hg__card-img"><img src="{{ asset('images/cp.jpeg') }}" alt=""></div><div class="hg__card-label">CAPS</div></a>
        <a class="hg__card hg__card--tall"  href="{{ route('products.index', ['search' => 'heel']) }}"><div class="hg__card-img"><img src="{{ asset('images/hh.jpeg') }}" alt=""></div><div class="hg__card-label">HEELS</div></a>
      </div>
 
 
    </div>
  </div>
 
</section>
<!-- =========================================================
     END HOT GRABS SECTION
     ========================================================= -->

<!-- =========================================================
     MARQUEE TICKER STRIP
     ========================================================= -->
<style>
  .marquee-container {
    display: block;
    width: 100%;
    background: #0a0a0a;
    border-top: 1px solid #0a0a0a;
    padding: 15px 0;
    margin: 0;
    overflow: hidden;
  }

  .marquee-content {
    display: flex;
    width: max-content;
    animation: marqueeScroll 26.4s linear infinite;
  }

  @keyframes marqueeScroll {
    0% {
      transform: translateX(0);
    }
    100% {
      transform: translateX(-50%);
    }
  }

  .marquee-item {
    font-family: 'Playfair Display', serif;
    font-size: 12px;
    font-style: italic;
    font-weight: 300;
    color: white;
    white-space: nowrap;
    padding: 0 44px;
    letter-spacing: 0.07em;
    position: relative;
  }

  .marquee-item::after {
    content: '·';
    font-size: 18px;
    color: rgba(255,255,255,0.25);
    line-height: 0;
    margin-left: 44px;
    position: absolute;
    right: 0;
  }

  .marquee-item:last-child::after {
    display: none;
  }
</style>

<div class="marquee-container">
  <div class="marquee-content">
    <!-- First set -->
    <span class="marquee-item">Ralph Lauren</span>
    <span class="marquee-item">Philippe Passainte</span>
    <span class="marquee-item">Serapian Milano</span>
    <span class="marquee-item">Dunhill</span>
    <span class="marquee-item">New Arrivals</span>
    <span class="marquee-item">Exclusive Pieces</span>
    <span class="marquee-item">Veyron Curated</span>
    <span class="marquee-item">Saison 2025</span>
    <span class="marquee-item">The Row</span>
    <span class="marquee-item">Loro Piana</span>
    <span class="marquee-item">Brunello Cucinelli</span>
    <span class="marquee-item">Jil Sander</span>
    <span class="marquee-item">Khaite</span>
    <span class="marquee-item">Lemaire</span>
    <span class="marquee-item">Totême</span>
    <span class="marquee-item">Sacai</span>
    <span class="marquee-item">Marni</span>
    <span class="marquee-item">Ami Paris</span>
    <span class="marquee-item">Enfants Riches Déprimés</span>
    <span class="marquee-item">Aadnevik</span>
    <span class="marquee-item">Interior</span>
    <span class="marquee-item">Our Legacy</span>
    <span class="marquee-item">Auralee</span>
    <span class="marquee-item">Coperni</span>
    <span class="marquee-item">Boito</span>
    <span class="marquee-item">MIUNIKU</span>
    <span class="marquee-item">Peter Do</span>
    <span class="marquee-item">Nanushka</span>

    <!-- Duplicate set for seamless loop -->
    <span class="marquee-item">Ralph Lauren</span>
    <span class="marquee-item">Philippe Passainte</span>
    <span class="marquee-item">Serapian Milano</span>
    <span class="marquee-item">Dunhill</span>
    <span class="marquee-item">New Arrivals</span>
    <span class="marquee-item">Exclusive Pieces</span>
    <span class="marquee-item">Veyron Curated</span>
    <span class="marquee-item">Saison 2025</span>
    <span class="marquee-item">The Row</span>
    <span class="marquee-item">Loro Piana</span>
    <span class="marquee-item">Brunello Cucinelli</span>
    <span class="marquee-item">Jil Sander</span>
    <span class="marquee-item">Khaite</span>
    <span class="marquee-item">Lemaire</span>
    <span class="marquee-item">Totême</span>
    <span class="marquee-item">Sacai</span>
    <span class="marquee-item">Marni</span>
    <span class="marquee-item">Ami Paris</span>
    <span class="marquee-item">Enfants Riches Déprimés</span>
    <span class="marquee-item">Aadnevik</span>
    <span class="marquee-item">Interior</span>
    <span class="marquee-item">Our Legacy</span>
    <span class="marquee-item">Auralee</span>
    <span class="marquee-item">Coperni</span>
    <span class="marquee-item">Boito</span>
    <span class="marquee-item">MIUNIKU</span>
    <span class="marquee-item">Peter Do</span>
    <span class="marquee-item">Nanushka</span>
  </div>
</div>
<!-- =========================================================
     END MARQUEE TICKER STRIP
     ========================================================= -->



















<div class="home-container">

<!-- MEN -->
<div class="poster-category-section">
<div class="category-header">
<h2 class="category-title">Distinguished Elegance</h2>
<p class="category-subtitle">Curated luxury for the refined gentleman</p>
</div>

<div class="poster-section">

<!-- RALPH LAUREN -->
<a href="{{ route('products.index', ['search' => 'Ralph Lauren']) }}" class="poster-card">
<div class="poster-wrapper">
<img src="{{ asset('images/ralph_lauren.jpg') }}" class="main-img" alt="">
<img src="{{ asset('images/ralph_lauren1.jpg') }}" class="hover-img" alt="">
<div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
<div class="veyron-watermark">VEYRON</div>
</div>
<div class="poster-brand">
<h3 class="brand-name">Ralph Lauren</h3>
<div class="brand-decoration"></div>
<p class="brand-tagline">Iconic American elegance for discerning gentlemen</p>
</div>
</a>

<!-- pp -->
<a href="{{ route('products.index', ['search' => 'Philippe passainle']) }}" class="poster-card">
<div class="poster-wrapper">
<img src="{{ asset('images/pp.jpg') }}" class="main-img">
<img src="{{ asset('images/pp1.jpg') }}" class="hover-img">
<div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
<div class="veyron-watermark">VEYRON</div>
</div>
<div class="poster-brand">
<h3 class="brand-name">Philippe passainle</h3>
<div class="brand-decoration"></div>
<p class="brand-tagline">Contemporary minimalist luxury for modern gentlemen</p>
</div>
</a>

<!-- sm -->
<a href="{{ route('products.index', ['search' => 'Serapian Mileno']) }}" class="poster-card">
<div class="poster-wrapper">
<img src="{{ asset('images/sm.jpg') }}" class="main-img">
<img src="{{ asset('images/sm1.jpg') }}" class="hover-img">
<div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
<div class="veyron-watermark">VEYRON</div>
</div>
<div class="poster-brand">
<h3 class="brand-name">Serapian Mileno</h3>
<div class="brand-decoration"></div>
<p class="brand-tagline">Contemporary essentials refined with timeless precision</p>
</div>
</a>

<!-- dd -->
<a href="{{ route('products.index', ['search' => 'Dunhil']) }}" class="poster-card">
<div class="poster-wrapper">
<img src="{{ asset('images/dn.jpg') }}" class="main-img">
<img src="{{ asset('images/dn1.jpg') }}" class="hover-img">
<div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
<div class="veyron-watermark">VEYRON</div>
</div>
<div class="poster-brand">
<h3 class="brand-name">Dunhil</h3>
<div class="brand-decoration"></div>
<p class="brand-tagline">British heritage crafted for modern gentlemen</p>
</div>
</a>

</div>
</div>

<!-- WOMEN -->
<div class="poster-category-section">
    <div class="category-header">
        <h2 class="category-title">Feminine Sophistication</h2>
        <p class="category-subtitle">The finest expressions of luxury and style</p>
    </div>

    <div class="poster-section">

        <a href="{{ route('products.index', ['search' => 'VOGUE']) }}" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/v1.jpeg') }}" class="main-img">
                <img src="{{ asset('images/v.jpeg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">VOGUE</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Bold modern femininity celebrated in every piece</p>
            </div>
        </a>

        <a href="{{ route('products.index', ['search' => 'PRADA']) }}" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/pa.jpg') }}" class="main-img">
                <img src="{{ asset('images/pa1.jpeg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">PRADA</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Elegant Italian minimalism elevated everyday chic</p>
            </div>
        </a>

        <a href="{{ route('products.index', ['search' => 'CHANEL']) }}" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/b.jpeg') }}" class="main-img">
                <img src="{{ asset('images/b1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">CHANEL</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Iconic Parisian elegance defining bold femininity</p>
            </div>
        </a>

        <a href="{{ route('products.index', ['search' => 'BALENCIAGA']) }}" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/bb.jpeg') }}" class="main-img">
                <img src="{{ asset('images/bb1.jpeg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">BALENCIAGA</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Spanish avant-garde luxury elegantly refined</p>
            </div>
        </a>

    </div>

</div>

<!-- SHOES -->
<div class="poster-category-section">
    <div class="category-header">
        <h2 class="category-title">Exceptional Footwear</h2>
        <p class="category-subtitle">Performance meets craftsmanship</p>
    </div>

    <div class="poster-section">

        <a href="{{ route('products.index', ['search' => 'Nike']) }}" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/nk.jpg') }}" class="main-img">
                <img src="{{ asset('images/nk1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">Nike</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Engineered peak athletic performance for champions</p>
            </div>
        </a>

        <a href="{{ route('products.index', ['search' => 'Puma']) }}" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/p.jpg') }}" class="main-img">
                <img src="{{ asset('images/p1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">Puma</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Where street culture meets authentic athletic spirit</p>
            </div>
        </a>

        <a href="{{ route('products.index', ['search' => 'Adidas']) }}" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/adi.jpg') }}" class="main-img">
                <img src="{{ asset('images/adi1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">Adidas</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Legendary three stripes defining athletic excellence</p>
            </div>
        </a>

        <a href="{{ route('products.index', ['search' => 'Formal Shoes']) }}" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/f1.jpg') }}" class="main-img">
                <img src="{{ asset('images/f.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">Formal Shoes</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Timeless craftsmanship for every formal occasion</p>
            </div>
        </a>

    </div>
</div>

<!-- GLASSES / EYEWEAR -->
<div class="poster-category-section">
    <div class="category-header">
        <h2 class="category-title">Signature Eyewear</h2>
        <p class="category-subtitle">Precision optics. Iconic design.</p>
    </div>

    <div class="poster-section">

        <a href="{{ route('products.index', ['search' => 'VERSAGE']) }}" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/ve.jpg') }}" class="main-img">
                <img src="{{ asset('images/ve1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">VERSAGE</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Bold contemporary frames defining modern authority</p>
            </div>
        </a>

        <a href="{{ route('products.index', ['search' => 'DOLCE & GABBANA']) }}" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/dg.jpg') }}" class="main-img">
                <img src="{{ asset('images/dg1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">DOLCE & GABBANA</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Italian luxury vision engineered for perfection</p>
            </div>
        </a>

        <a href="{{ route('products.index', ['search' => 'GUCCI']) }}" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/gc.jpg') }}" class="main-img">
                <img src="{{ asset('images/gc1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">GUCCI</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Iconic luxury elegantly framed in refinement</p>
            </div>
        </a>

        <a href="{{ route('products.index', ['search' => 'RAY-BAN']) }}" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/rb.jpg') }}" class="main-img">
                <img src="{{ asset('images/rb1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">RAY-BAN</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Iconic vision defining timeless eyewear legacy</p>
            </div>
        </a>

    </div>
</div>







</div>
@endsection

@push('scripts')
<script>
// Image rotation for MEN and WOMEN hero cards every 4 seconds
const menImages = ['men1.jpeg', 'men2.jpeg', 'men3.jpeg', 'men4.jpeg', 'men5.jpeg', 'men6.jpeg'];
const womenImages = ['wo1.jpeg', 'wo2.jpeg', 'wo3.jpeg', 'wo4.jpeg', 'wo5.jpeg', 'wo6.jpeg','wo7.jpeg', 'wo8.jpeg', 'wo9.jpeg'];

let menIndex = 0;
let womenIndex = 0;

setInterval(function() {
    const menImg = document.getElementById('menHeroImg');
    const womenImg = document.getElementById('womenHeroImg');
    
    if(menImg) {
        menIndex = (menIndex + 1) % menImages.length;
        menImg.src = '/images/' + menImages[menIndex];
    }
    
    if(womenImg) {
        womenIndex = (womenIndex + 1) % womenImages.length;
        womenImg.src = '/images/' + womenImages[womenIndex];
    }
}, 4000);

document.addEventListener('DOMContentLoaded',function(){
    const videoContainer=document.getElementById('videoContainer');
    const startVideo=document.getElementById('startVideo');
    const videoPlayedKey='veyron_video_played_session';

    // Check if video has already been played this session
    if(sessionStorage.getItem(videoPlayedKey)){
        videoContainer.style.display='none';
        return;
    }

    // Show the video container
    videoContainer.style.display='block';

    // Try to play video
    const playPromise=startVideo.play();
    if(playPromise!==undefined){
        playPromise.then(function(){
            console.log('Video playing');
        }).catch(function(error){
            console.log('Autoplay prevented, trying on user interaction');
            document.addEventListener('click',function(){
                startVideo.play();
            },{once:true});
        });
    }

    // Handle video end
    startVideo.addEventListener('ended',function(){
        hideVideo();
    });

    // Hide video after 5 seconds if it doesn't play
    setTimeout(function(){
        if(startVideo.currentTime===0&&!startVideo.paused){
            return;
        }
    },5000);

    // Function to hide video with smooth animation
    function hideVideo(){
        videoContainer.style.transition='opacity 0.3s ease-out';
        videoContainer.style.opacity='0';
        setTimeout(function(){
            videoContainer.style.display='none';
            sessionStorage.setItem(videoPlayedKey,'true');
        },300);
    }
});
</script>
@endpush
