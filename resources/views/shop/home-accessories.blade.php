@extends('layouts.app')

@section('title', 'VEYRON — Accessories')

@push('styles')
<style>
    body { font-family: 'Poppins', sans-serif; margin:0; padding:0; background:#f4f4f4; }

    .home-container { max-width:1200px; margin:2rem auto; padding:0 1rem; display:flex; flex-direction:column; gap:2rem; }

    .category-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #222;
        margin: 2rem 0 1rem 0;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-align: center;
    }

    .category-subtitle {
        text-align: center;
        color: #666;
        font-size: 1rem;
        margin-bottom: 2rem;
        font-weight: 300;
    }

    /* Poster Section */
    .poster-section {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .poster-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        text-decoration: none;
        color: inherit;
    }
    .poster-card:hover { transform: translateY(-5px); }

    .poster-wrapper { position: relative; }
    .poster-wrapper img {
        width: 100%;
        height: 350px;
        object-fit: cover;
        display: block;
        border-bottom: 1px solid #eee;
    }

    .veyron-watermark {
        position: absolute;
        top: 12px;
        right: 15px;
        font-size: 22px;
        font-weight: bold;
        color: rgba(255,255,255,0.7);
        text-shadow: 0 0 6px rgba(0,0,0,0.4);
        opacity: 0.9;
    }

    .poster-text {
        padding: 10px 5px 18px;
        font-size: 0.95rem;
        color: #333;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .home-container { margin: 1rem auto; padding: 0 0.5rem; }
        .category-title { font-size: 1.8rem; }
        .poster-section { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
        .poster-wrapper img { height: 250px; }
    }
</style>
@endpush

@section('content')
<!-- Premium Banner Carousel -->
@include('components.banner-carousel', ['banners' => $banners])

<div class="home-container">
    <h1 class="category-title">Accessories</h1>
    <p class="category-subtitle">Complete your look with our premium accessories collection</p>

    <!-- Featured Products Section -->
    <div class="poster-section">
        @foreach($posters as $poster)
            <a href="{{ $poster['link'] }}" class="poster-card">
                <div class="poster-wrapper">
                    <img src="{{ asset($poster['file']) }}" alt="Poster">
                    <div class="veyron-watermark">VEYRON</div>
                </div>
                <div class="poster-text">{{ $poster['line1'] }}<br>{{ $poster['line2'] }}</div>
            </a>
        @endforeach
    </div>
</div>
@endsection
