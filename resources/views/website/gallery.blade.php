{{-- resources/views/website/gallery.blade.php --}}
@extends('layouts.public')

@section('title', 'Gallery - RSCB')

@push('styles')
<style>
    .gallery-section {
        padding: 60px 0;
    }

    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-header h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1a1a2e;
    }

    .section-header p {
        color: #666;
        font-size: 1.1rem;
    }

    /* Category Tabs with Horizontal Scroll */
    .category-nav-wrapper {
        position: relative;
        margin-bottom: 30px;
    }

    .category-nav {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding: 10px 0;
        scroll-behavior: smooth;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .category-nav::-webkit-scrollbar {
        display: none;
    }

    .category-nav .nav-item {
        flex-shrink: 0;
    }

    .category-nav .nav-link {
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: 500;
        color: #555;
        background: #f8f9fa;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        white-space: nowrap;
        font-size: 14px;
    }

    .category-nav .nav-link:hover {
        background: #e9ecef;
        color: #333;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .category-nav .nav-link.active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(102,126,234,0.3);
    }

    .scroll-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: 1px solid #e0e0e0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        cursor: pointer;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        font-size: 18px;
        color: #667eea;
    }

    .scroll-arrow:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .scroll-arrow-left {
        left: -20px;
    }

    .scroll-arrow-right {
        right: -20px;
    }

    .scroll-arrow.hidden {
        opacity: 0;
        pointer-events: none;
    }

    /* Gallery Grid */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .gallery-item {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        cursor: pointer;
        background: #fff;
        box-shadow: 0 2px 15px rgba(0,0,0,0.06);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        aspect-ratio: 4/3;
    }

    .gallery-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .gallery-item:hover img {
        transform: scale(1.1);
    }

    .gallery-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        padding: 40px 20px 20px;
        color: white;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.4s ease;
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
        transform: translateY(0);
    }

    .gallery-overlay h5 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .gallery-overlay small {
        font-size: 12px;
        opacity: 0.8;
    }

    .gallery-overlay .view-icon {
        position: absolute;
        top: -25px;
        right: 20px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 5px 20px rgba(102,126,234,0.4);
    }

    /* Lightbox Modal */
    .lightbox-modal .modal-dialog {
        max-width: 95vw;
        margin: 20px auto;
    }

    .lightbox-modal .modal-content {
        background: #1a1a2e;
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }

    .lightbox-modal .modal-header {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding: 15px 20px;
    }

    .lightbox-modal .modal-title {
        color: white;
        font-size: 16px;
    }

    .lightbox-modal .btn-close {
        filter: brightness(0) invert(1);
    }

    .lightbox-modal .modal-body {
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #0a0a1a;
        min-height: 60vh;
    }

    .lightbox-modal .modal-body img {
        max-height: 80vh;
        max-width: 100%;
        object-fit: contain;
    }

    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.2);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 24px;
        cursor: pointer;
        transition: all 0.3s;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
    }

    .lightbox-nav:hover {
        background: rgba(255,255,255,0.4);
        transform: translateY(-50%) scale(1.1);
    }

    .lightbox-prev {
        left: 20px;
    }

    .lightbox-next {
        right: 20px;
    }

    .lightbox-counter {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        background: rgba(0,0,0,0.5);
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 14px;
        backdrop-filter: blur(10px);
    }

    .lightbox-footer {
        background: #1a1a2e;
        border-top: 1px solid rgba(255,255,255,0.1);
        padding: 10px 20px;
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .lightbox-footer .btn {
        border-radius: 50px;
        padding: 8px 20px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state i {
        font-size: 80px;
        color: #ddd;
        margin-bottom: 20px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
        }

        .scroll-arrow {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }

        .scroll-arrow-left { left: -10px; }
        .scroll-arrow-right { right: -10px; }

        .category-nav .nav-link {
            padding: 8px 16px;
            font-size: 13px;
        }

        .lightbox-nav {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
    }
</style>
@endpush

@section('content')
<section class="gallery-section">
    <div class="container">
        {{-- Header --}}
        <div class="section-header">
            <h2>Sports Gallery</h2>
            <p>Capturing the spirit of sports across all regions</p>
        </div>

        {{-- Category Tabs with Horizontal Scroll --}}
        <div class="category-nav-wrapper">
            <button class="scroll-arrow scroll-arrow-left hidden" onclick="scrollCategories(-300)" aria-label="Scroll left">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="category-nav" id="categoryNav">
                <a href="{{ route('website.gallery') }}"
                   class="nav-link {{ !request('category') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill me-1"></i>All Photos
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('website.gallery', ['category' => $category]) }}"
                       class="nav-link {{ request('category') == $category ? 'active' : '' }}">
                        {{ $category }}
                    </a>
                @endforeach
            </div>

            <button class="scroll-arrow scroll-arrow-right" onclick="scrollCategories(300)" aria-label="Scroll right">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        {{-- Gallery Grid --}}
        @if($images->count() > 0)
            <div class="gallery-grid" id="galleryGrid">
                @foreach($images as $index => $image)
                    <div class="gallery-item"
                         onclick="openLightbox({{ $index }})"
                         data-src="{{ asset('storage/' . $image->image_path) }}"
                         data-title="{{ $image->title }}"
                         data-category="{{ $image->category }}">
                        <img src="{{ asset('storage/' . $image->thumbnail_path) }}"
                             alt="{{ $image->title }}"
                             loading="lazy"
                             onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22300%22><rect fill=%22%23667eea%22 width=%22400%22 height=%22300%22/><text fill=%22white%22 x=%22200%22 y=%22160%22 text-anchor=%22middle%22 font-size=%2230%22>RSCB</text></svg>'">
                        <div class="gallery-overlay">
                            <div class="view-icon">
                                <i class="bi bi-eye"></i>
                            </div>
                            <h5>{{ $image->title }}</h5>
                            @if($image->category)
                                <small><i class="bi bi-folder me-1"></i>{{ $image->category }}</small>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-5">
                {{ $images->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-images"></i>
                <h4>No Images Yet</h4>
                <p class="text-muted">Gallery images will appear here once uploaded.</p>
                <a href="{{ route('website.events') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-calendar-event me-2"></i>View Events
                </a>
            </div>
        @endif
    </div>
</section>

{{-- Lightbox Modal --}}
<div class="modal fade lightbox-modal" id="lightboxModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lightboxTitle">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body position-relative">
                <button class="lightbox-nav lightbox-prev" onclick="navigateLightbox(-1)" title="Previous">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <img src="" id="lightboxImage" alt="Gallery Image">

                <button class="lightbox-nav lightbox-next" onclick="navigateLightbox(1)" title="Next">
                    <i class="bi bi-chevron-right"></i>
                </button>

                <div class="lightbox-counter" id="lightboxCounter">1 / {{ $images->count() }}</div>
            </div>
            <div class="lightbox-footer">
                <a href="#" id="lightboxDownload" class="btn btn-outline-light btn-sm" download>
                    <i class="bi bi-download me-1"></i>Download
                </a>
                <button class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Store all images data
    const galleryImages = [
        @foreach($images as $image)
        {
            src: '{{ asset('storage/' . $image->image_path) }}',
            thumbnail: '{{ asset('storage/' . $image->thumbnail_path) }}',
            title: '{{ $image->title }}',
            category: '{{ $image->category }}'
        },
        @endforeach
    ];

    let currentImageIndex = 0;
    const totalImages = galleryImages.length;

    // Category horizontal scroll
    function scrollCategories(amount) {
        const container = document.getElementById('categoryNav');
        container.scrollBy({ left: amount, behavior: 'smooth' });
    }

    // Update scroll arrow visibility
    function updateScrollArrows() {
        const container = document.getElementById('categoryNav');
        const leftArrow = document.querySelector('.scroll-arrow-left');
        const rightArrow = document.querySelector('.scroll-arrow-right');

        if (container.scrollLeft <= 10) {
            leftArrow.classList.add('hidden');
        } else {
            leftArrow.classList.remove('hidden');
        }

        if (container.scrollLeft + container.clientWidth >= container.scrollWidth - 10) {
            rightArrow.classList.add('hidden');
        } else {
            rightArrow.classList.remove('hidden');
        }
    }

    // Listen for scroll events
    document.getElementById('categoryNav').addEventListener('scroll', updateScrollArrows);
    window.addEventListener('resize', updateScrollArrows);
    document.addEventListener('DOMContentLoaded', updateScrollArrows);

    // Open lightbox
    function openLightbox(index) {
        currentImageIndex = index;
        updateLightboxImage();

        const modal = new bootstrap.Modal(document.getElementById('lightboxModal'));
        modal.show();
    }

    // Navigate lightbox
    function navigateLightbox(direction) {
        currentImageIndex += direction;

        if (currentImageIndex < 0) {
            currentImageIndex = totalImages - 1;
        } else if (currentImageIndex >= totalImages) {
            currentImageIndex = 0;
        }

        updateLightboxImage();
    }

    // Update lightbox content
    function updateLightboxImage() {
        const image = galleryImages[currentImageIndex];

        document.getElementById('lightboxImage').src = image.src;
        document.getElementById('lightboxTitle').textContent = image.title;
        document.getElementById('lightboxDownload').href = image.src;
        document.getElementById('lightboxDownload').download = image.title + '.jpg';
        document.getElementById('lightboxCounter').textContent =
            (currentImageIndex + 1) + ' / ' + totalImages;
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('lightboxModal');
        if (modal.classList.contains('show')) {
            if (e.key === 'ArrowLeft') {
                navigateLightbox(-1);
            } else if (e.key === 'ArrowRight') {
                navigateLightbox(1);
            } else if (e.key === 'Escape') {
                bootstrap.Modal.getInstance(modal).hide();
            }
        }
    });

    // Touch/swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;

    document.getElementById('lightboxImage').addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    });

    document.getElementById('lightboxImage').addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        const diff = touchStartX - touchEndX;

        if (Math.abs(diff) > 50) {
            if (diff > 0) {
                navigateLightbox(1);
            } else {
                navigateLightbox(-1);
            }
        }
    });

    // Lazy load images with intersection observer
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    imageObserver.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px'
        });

        document.querySelectorAll('.gallery-item img[loading="lazy"]').forEach(img => {
            imageObserver.observe(img);
        });
    }
</script>
@endpush
