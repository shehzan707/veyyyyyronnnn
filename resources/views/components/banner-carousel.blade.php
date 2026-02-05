<!-- Premium Banner Carousel Component -->
<div class="veyron-banner-carousel-wrapper">
    <!-- Main Banner Display -->
    <div class="veyron-banner-carousel" id="veyronBannerCarousel">
        @forelse($banners as $index => $banner)
            <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                <div class="slide-content">
                    @if($banner->media_type === 'image')
                        <img src="{{ asset($banner->file_path) }}" 
                             alt="{{ $banner->file_name }}" 
                             class="banner-media"
                             data-type="image">
                    @elseif($banner->media_type === 'video')
                        <video class="banner-media" 
                               data-type="video" 
                               playsinline 
                               muted 
                               autoplay 
                               loop
                               preload="metadata"
                               style="width: 100%; height: 100%; object-fit: cover;">
                            <source src="{{ asset($banner->file_path) }}" type="video/mp4">
                            <source src="{{ asset(str_replace('.mp4', '.webm', $banner->file_path)) }}" type="video/webm">
                            Your browser does not support the video tag.
                        </video>
                    @endif
                </div>
                <!-- Link Overlay -->
                @if($banner->banner_link)
                    <a href="{{ isset($filterUrl) ? $filterUrl : $banner->banner_link }}" class="slide-link-overlay" title="View products"></a>
                @else
                    <a href="{{ isset($filterUrl) ? $filterUrl : route('products.index') }}" class="slide-link-overlay" title="View products"></a>
                @endif
            </div>
        @empty
            <div class="carousel-slide active">
                <div class="slide-content no-banners">
                    <p>No banners available for this section</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Navigation Controls -->
    @if($banners->count() > 1)
        <button class="carousel-nav carousel-nav-prev" id="carouselPrev" aria-label="Previous banner">
            <span>&#10094;</span>
        </button>
        <button class="carousel-nav carousel-nav-next" id="carouselNext" aria-label="Next banner">
            <span>&#10095;</span>
        </button>

        <!-- Dots Indicator -->
        <div class="carousel-indicators">
            @foreach($banners as $index => $banner)
                <button class="indicator-dot {{ $index === 0 ? 'active' : '' }}" 
                        data-slide="{{ $index }}"
                        aria-label="Go to slide {{ $index + 1 }}">
                </button>
            @endforeach
        </div>
    @endif

    <!-- Progress Bar (for auto-slide timing) -->
    <div class="carousel-progress">
        <div class="progress-bar"></div>
    </div>
</div>

<style>
    /* Carousel Container */
    .veyron-banner-carousel-wrapper {
        position: relative;
        width: 100%;
        height: 91vh;
        overflow: hidden;
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    }

    /* Main Carousel */
    .veyron-banner-carousel {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    /* Carousel Slides */
    .carousel-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.8s cubic-bezier(0.645, 0.045, 0.355, 1);
        z-index: 1;
    }

    .carousel-slide.active {
        opacity: 1;
        z-index: 10;
    }

    /* Slide Content */
    .slide-content {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #000;
        overflow: hidden;
    }

    .banner-media {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        image-rendering: crisp-edges;
        image-rendering: -webkit-optimize-contrast;
        -ms-interpolation-mode: nearest-neighbor;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }

    /* No Banners State */
    .slide-content.no-banners {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        color: #888;
        text-align: center;
    }

    .slide-content.no-banners p {
        font-size: 1.5rem;
        font-weight: 300;
        letter-spacing: 1px;
    }

    /* Link Overlay */
    .slide-link-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 5;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .slide-link-overlay:hover {
        background: rgba(0, 0, 0, 0.05);
    }

    /* Navigation Buttons */
    .carousel-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        border: none;
        padding: 16px 20px;
        font-size: 24px;
        cursor: pointer;
        z-index: 20;
        transition: all 0.3s ease;
        backdrop-filter: blur(8px);
        border-radius: 4px;
        line-height: 1;
    }

    .carousel-nav:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-50%) scale(1.1);
    }

    .carousel-nav-prev {
        left: 30px;
    }

    .carousel-nav-next {
        right: 30px;
    }

    /* Indicators/Dots */
    .carousel-indicators {
        position: absolute;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 12px;
        z-index: 20;
    }

    .indicator-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.6);
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 0;
    }

    .indicator-dot:hover {
        background: rgba(255, 255, 255, 0.6);
        transform: scale(1.2);
    }

    .indicator-dot.active {
        background: rgba(255, 255, 255, 0.95);
        border-color: rgba(255, 255, 255, 1);
        width: 14px;
        height: 14px;
    }

    /* Progress Bar */
    .carousel-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: rgba(255, 255, 255, 0.1);
        z-index: 20;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.4));
        width: 0%;
        transition: width 0.1s linear;
    }

    /* Responsive Adjustments */
    @media (max-width: 1024px) {
        .carousel-nav {
            padding: 12px 16px;
            font-size: 20px;
        }

        .carousel-nav-prev {
            left: 15px;
        }

        .carousel-nav-next {
            right: 15px;
        }

        .carousel-indicators {
            bottom: 25px;
            gap: 8px;
        }
    }

    @media (max-width: 768px) {
        .veyron-banner-carousel-wrapper {
            height: 70vh;
        }

        .carousel-nav {
            padding: 10px 12px;
            font-size: 18px;
            opacity: 0.7;
        }

        .carousel-nav:hover {
            opacity: 1;
        }

        .carousel-indicators {
            bottom: 15px;
            gap: 6px;
        }

        .indicator-dot {
            width: 10px;
            height: 10px;
        }

        .indicator-dot.active {
            width: 12px;
            height: 12px;
        }
    }

    @media (max-width: 480px) {
        .veyron-banner-carousel-wrapper {
            height: 50vh;
        }

        .carousel-nav {
            display: none;
        }

        .carousel-indicators {
            bottom: 10px;
            gap: 4px;
        }

        .indicator-dot {
            width: 8px;
            height: 8px;
        }

        .carousel-progress {
            height: 2px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('veyronBannerCarousel');
    const slides = carousel ? carousel.querySelectorAll('.carousel-slide') : [];
    const indicators = document.querySelectorAll('.indicator-dot');
    const prevBtn = document.getElementById('carouselPrev');
    const nextBtn = document.getElementById('carouselNext');
    const progressBar = document.querySelector('.progress-bar');

    if (slides.length <= 1) return; // No carousel if single or no banners

    let currentIndex = 0;
    let autoSlideTimer;
    let progressTimer;
    const autoSlideDuration = 5000; // 5 seconds for images
    let isVideoPlaying = false;

    // Helper: Get current video element if exists
    function getCurrentVideo() {
        const currentSlide = slides[currentIndex];
        return currentSlide.querySelector('video');
    }

    // Helper: Check if current slide is video
    function isCurrentSlideVideo() {
        const currentSlide = slides[currentIndex];
        const media = currentSlide.querySelector('.banner-media');
        return media && media.dataset.type === 'video';
    }

    // Show slide
    function showSlide(index) {
        if (index < 0) index = slides.length - 1;
        if (index >= slides.length) index = 0;

        currentIndex = index;

        // Update slides
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            if (i === index) slide.classList.add('active');
        });

        // Update indicators
        indicators.forEach((dot, i) => {
            dot.classList.remove('active');
            if (i === index) dot.classList.add('active');
        });

        // Handle video logic
        const currentVideo = getCurrentVideo();
        if (isCurrentSlideVideo() && currentVideo) {
            currentVideo.play().catch(err => console.log('Video autoplay blocked:', err));
            isVideoPlaying = true;
        } else {
            isVideoPlaying = false;
            startAutoSlide();
        }

        resetProgress();
    }

    // Start auto-slide timer
    function startAutoSlide() {
        clearTimeout(autoSlideTimer);
        autoSlideTimer = setTimeout(() => nextSlide(), autoSlideDuration);
        startProgress();
    }

    // Next slide
    function nextSlide() {
        showSlide(currentIndex + 1);
    }

    // Previous slide
    function prevSlide() {
        showSlide(currentIndex - 1);
    }

    // Progress bar animation
    function startProgress() {
        if (progressBar) {
            progressBar.style.animation = 'none';
            setTimeout(() => {
                progressBar.style.animation = `slideProgress ${autoSlideDuration}ms linear forwards`;
            }, 10);
        }
    }

    function resetProgress() {
        if (progressBar) {
            progressBar.style.animation = 'none';
            progressBar.style.width = '0%';
        }
    }

    // Event Listeners
    if (prevBtn) prevBtn.addEventListener('click', () => {
        prevSlide();
    });

    if (nextBtn) nextBtn.addEventListener('click', () => {
        nextSlide();
    });

    indicators.forEach((dot, index) => {
        dot.addEventListener('click', () => showSlide(index));
    });

    // Video end listener
    slides.forEach((slide) => {
        const video = slide.querySelector('video');
        if (video) {
            video.addEventListener('ended', () => {
                if (currentIndex === slides.indexOf(slide)) {
                    nextSlide();
                }
            });
        }
    });

    // Pause on hover
    carousel.addEventListener('mouseenter', () => {
        clearTimeout(autoSlideTimer);
        clearTimeout(progressTimer);
    });

    carousel.addEventListener('mouseleave', () => {
        if (!isVideoPlaying) {
            startAutoSlide();
        }
    });

    // Add progress animation keyframes
    if (!document.getElementById('bannerProgressStyles')) {
        const style = document.createElement('style');
        style.id = 'bannerProgressStyles';
        style.textContent = `
            @keyframes slideProgress {
                from { width: 0%; }
                to { width: 100%; }
            }
        `;
        document.head.appendChild(style);
    }

    // Initialize
    showSlide(0);
});
</script>
