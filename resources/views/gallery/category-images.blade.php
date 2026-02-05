@extends('layouts.app')

@section('title', 'Product Gallery — ' . ucfirst($category))

@push('styles')
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
        margin: 0;
        padding: 0;
    }

    .gallery-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 3rem 1rem;
    }

    .gallery-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .gallery-header h1 {
        font-size: 3rem;
        font-weight: 900;
        margin: 0 0 1rem;
        color: #000;
        letter-spacing: 2px;
        text-transform: capitalize;
    }

    .gallery-header p {
        font-size: 1.1rem;
        color: #666;
        margin: 0;
    }

    .image-count {
        display: inline-block;
        background: #000;
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        margin-top: 1rem;
        font-weight: 600;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 2rem;
        color: #000;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s;
    }

    .back-link:hover {
        color: #666;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .image-card {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .image-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }

    .image-wrapper {
        width: 100%;
        height: 200px;
        overflow: hidden;
        background: #f0f0f0;
    }

    .image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .image-card:hover .image-wrapper img {
        transform: scale(1.05);
    }

    .image-info {
        padding: 1rem;
    }

    .image-name {
        font-size: 0.85rem;
        font-weight: 600;
        color: #000;
        margin: 0 0 0.5rem;
        line-height: 1.3;
        word-break: break-word;
        white-space: normal;
        max-height: 3em;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .image-size {
        font-size: 0.75rem;
        color: #999;
        margin: 0;
    }

    .view-full {
        display: inline-block;
        width: 100%;
        padding: 0.5rem;
        background: #000;
        color: #fff;
        text-align: center;
        font-size: 0.8rem;
        border: none;
        cursor: pointer;
        font-weight: 600;
        border-radius: 0 0 8px 8px;
        transition: background 0.3s;
    }

    .view-full:hover {
        background: #333;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
    }

    .modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-width: 90vw;
        max-height: 90vh;
        object-fit: contain;
    }

    .modal-close {
        position: absolute;
        top: 20px;
        right: 30px;
        color: #f1f1f1;
        font-size: 2rem;
        font-weight: bold;
        cursor: pointer;
    }

    .modal-close:hover {
        color: #bbb;
    }

    .empty-state {
        grid-column: 1/-1;
        text-align: center;
        padding: 3rem 1rem;
        color: #666;
    }

    @media (max-width: 768px) {
        .gallery-header h1 {
            font-size: 1.8rem;
        }

        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }

        .image-wrapper {
            height: 150px;
        }
    }
</style>
@endpush

@section('content')
<div class="gallery-container">
    <a href="{{ route('gallery.all') }}" class="back-link">← Back to All Images</a>

    <div class="gallery-header">
        <h1>{{ $category }} Images</h1>
        <p>Showing {{ $totalImages }} images in this category</p>
        @if($totalImages > 0)
            <div class="image-count">{{ $totalImages }} Images</div>
        @endif
    </div>

    <div class="gallery-grid">
        @forelse($images as $image)
            <div class="image-card">
                <div class="image-wrapper">
                    <img src="{{ $image['url'] }}" alt="{{ $image['name'] }}" class="gallery-image">
                </div>
                <div class="image-info">
                    <p class="image-name" title="{{ $image['name'] }}">{{ $image['name'] }}</p>
                    <p class="image-size">{{ number_format($image['size'] / 1024, 1) }} KB</p>
                </div>
                <button class="view-full" onclick="openModal(this)">View Full</button>
            </div>
        @empty
            <div class="empty-state">
                <p>No images found for "{{ $category }}"</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal">
    <span class="modal-close" onclick="closeModal()">&times;</span>
    <img id="modalImage" class="modal-content" alt="">
</div>

<script>
    function openModal(btn) {
        const img = btn.previousElementSibling.querySelector('.gallery-image');
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        
        modalImg.src = img.src;
        modal.style.display = 'block';
    }

    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('imageModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endsection
