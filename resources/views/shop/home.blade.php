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
    margin:5rem 0;
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
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:3.5rem 2.5rem;
}

/* CARD */
.poster-card{
    text-decoration:none;
    color:inherit;
    display:flex;
    flex-direction:column;
    transition:all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    position:relative;
}

/* IMAGE WRAPPER */
.poster-wrapper{
    position:relative;
    aspect-ratio:280/380;
    overflow:hidden;
    border-radius:0;
    background:#f5f5f5;
    box-shadow:0 8px 24px rgba(0,0,0,0.12);
    transition:transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94),box-shadow 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.poster-card:hover .poster-wrapper{
    transform:translateY(-12px) scale(1.01);
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
    padding:1.8rem 1rem;
    transition:all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.brand-name{
    font-size:1.35rem;
    font-weight:700;
    letter-spacing:1.5px;
    color:#1a1a1a;
    transition:all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.brand-decoration{
    width:50px;
    height:1.5px;
    background:#333;
    margin:0.8rem auto;
    transition:all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    box-shadow:0 2px 4px rgba(0,0,0,0.1);
}

.poster-card:hover .brand-decoration{
    width:85px;
}

.brand-tagline{
    font-size:0.8rem;
    color:#999;
    letter-spacing:1px;
    font-weight:500;
    transition:all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
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
<!-- FULLSCREEN VIDEO -->
<div class="fullscreen-video-container" id="videoContainer">
    <video id="startVideo" autoplay muted playsinline style="display:block;" preload="auto">
        <source src="{{ asset('images/start.mp4') }}" type="video/mp4">
        <source src="{{ asset('images/start.m4p') }}" type="video/mp4">
    </video>
</div>

@include('components.banner-carousel',['banners'=>$banners])

<div class="home-container">

<!-- MEN -->
<div class="poster-category-section">
<div class="category-header">
<h2 class="category-title">Distinguished Elegance</h2>
<p class="category-subtitle">Curated luxury for the refined gentleman</p>
</div>

<div class="poster-section">

<!-- RALPH LAUREN -->
<a href="/shop/men" class="poster-card">
<div class="poster-wrapper">
<img src="{{ asset('images/ralph_lauren.jpg') }}" class="main-img" alt="">
<img src="{{ asset('images/ralph_lauren1.jpg') }}" class="hover-img" alt="">
<div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
<div class="veyron-watermark">VEYRON</div>
</div>
<div class="poster-brand">
<h3 class="brand-name">Ralph Lauren</h3>
<div class="brand-decoration"></div>
<p class="brand-tagline">Timeless American Heritage</p>
</div>
</a>

<!-- pp -->
<a href="/shop/men" class="poster-card">
<div class="poster-wrapper">
<img src="{{ asset('images/pp.jpg') }}" class="main-img">
<img src="{{ asset('images/pp1.jpg') }}" class="hover-img">
<div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
<div class="veyron-watermark">VEYRON</div>
</div>
<div class="poster-brand">
<h3 class="brand-name">Philippe passainle</h3>
<div class="brand-decoration"></div>
<p class="brand-tagline">Modern Minimalism</p>
</div>
</a>

<!-- sm -->
<a href="/shop/men" class="poster-card">
<div class="poster-wrapper">
<img src="{{ asset('images/sm.jpg') }}" class="main-img">
<img src="{{ asset('images/sm1.jpg') }}" class="hover-img">
<div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
<div class="veyron-watermark">VEYRON</div>
</div>
<div class="poster-brand">
<h3 class="brand-name">Serapian Mileno</h3>
<div class="brand-decoration"></div>
<p class="brand-tagline">Contemporary Essentials</p>
</div>
</a>

<!-- dd -->
<a href="/shop/men" class="poster-card">
<div class="poster-wrapper">
<img src="{{ asset('images/dn.jpg') }}" class="main-img">
<img src="{{ asset('images/dn1.jpg') }}" class="hover-img">
<div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
<div class="veyron-watermark">VEYRON</div>
</div>
<div class="poster-brand">
<h3 class="brand-name">Dunhil</h3>
<div class="brand-decoration"></div>
<p class="brand-tagline">Denim Authority</p>
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

        <a href="/shop/women" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/v1.jpeg') }}" class="main-img">
                <img src="{{ asset('images/v.jpeg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">VOGUE</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Modern Femininity</p>
            </div>
        </a>

        <a href="/shop/women" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/pa.jpg') }}" class="main-img">
                <img src="{{ asset('images/pa1.jpeg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">PRADA</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Everyday Chic</p>
            </div>
        </a>

        <a href="/shop/women" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/b.jpeg') }}" class="main-img">
                <img src="{{ asset('images/b1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">CHANEL</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Bold Glamour</p>
            </div>
        </a>

        <a href="/shop/women" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/bb.jpeg') }}" class="main-img">
                <img src="{{ asset('images/bb1.jpeg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">BALENCIAGA</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Refined Style</p>
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

        <a href="/shop/footwear" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/nk.jpg') }}" class="main-img">
                <img src="{{ asset('images/nk1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">Nike</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Engineered Performance</p>
            </div>
        </a>

        <a href="/shop/footwear" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/p.jpg') }}" class="main-img">
                <img src="{{ asset('images/p1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">Puma</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Sport Meets Street</p>
            </div>
        </a>

        <a href="/shop/footwear" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/adi.jpg') }}" class="main-img">
                <img src="{{ asset('images/adi1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">Adidas</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Three Stripes Legacy</p>
            </div>
        </a>

        <a href="/shop/footwear" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/f1.jpg') }}" class="main-img">
                <img src="{{ asset('images/f.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">Formal Shoes</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Timeless Craft</p>
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

        <a href="/shop/glasses" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/ve.jpg') }}" class="main-img">
                <img src="{{ asset('images/ve1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">VERSAGE</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Bold frame mordern Authority</p>
            </div>
        </a>

        <a href="/shop/glasses" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/dg.jpg') }}" class="main-img">
                <img src="{{ asset('images/dg1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">DOLCE & GABBANA</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Engineered For Performance Vision</p>
            </div>
        </a>

        <a href="/shop/glasses" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/gc.jpg') }}" class="main-img">
                <img src="{{ asset('images/gc1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">GUCCI</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Luxury Framed In Elegance</p>
            </div>
        </a>

        <a href="/shop/glasses" class="poster-card">
            <div class="poster-wrapper">
                <img src="{{ asset('images/rb.jpg') }}" class="main-img">
                <img src="{{ asset('images/rb1.jpg') }}" class="hover-img">
                <div class="poster-overlay"><span class="overlay-text">Explore Collection</span></div>
                <div class="veyron-watermark">VEYRON</div>
            </div>
            <div class="poster-brand">
                <h3 class="brand-name">RAY-BUN</h3>
                <div class="brand-decoration"></div>
                <p class="brand-tagline">Iconic Vision Since 1937</p>
            </div>
        </a>

    </div>
</div>







</div>
@endsection

@push('scripts')
<script>
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
