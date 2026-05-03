<section class="about-gallery" style="margin-bottom: 10px;">
    <div class="container">
        <h2 class="section-title">{{ $aboutGallery['title'] ?? 'Gallery' }}</h2>
        <div class="about-gallery__grid">
            @foreach (($aboutGallery['images'] ?? []) as $item)
                <figure>
                    <button
                        type="button"
                        class="about-gallery__open"
                        data-about-gallery-open
                        data-src="{{ asset($item['image'] ?? '') }}"
                    >
                        <img src="{{ asset($item['image'] ?? '') }}" alt="{{ $item['alt'] ?? 'Poema Tours gallery image' }}">
                    </button>
                </figure>
            @endforeach
        </div>
    </div>
</section>
<div class="about-gallery-lightbox" data-about-gallery-lightbox aria-hidden="true">
    <button type="button" class="about-gallery-lightbox__close" data-about-gallery-close aria-label="Close gallery">&times;</button>
    <button type="button" class="about-gallery-lightbox__nav about-gallery-lightbox__nav--prev" data-about-gallery-prev aria-label="Previous image">&#8249;</button>
    <div class="about-gallery-lightbox__stage" data-about-gallery-stage>
        <img src="" alt="About gallery full screen image" data-about-gallery-image>
    </div>
    <button type="button" class="about-gallery-lightbox__nav about-gallery-lightbox__nav--next" data-about-gallery-next aria-label="Next image">&#8250;</button>
    <div class="about-gallery-lightbox__zoom">
        <button type="button" data-about-gallery-zoom-in aria-label="Zoom in">+</button>
        <button type="button" data-about-gallery-zoom-out aria-label="Zoom out">-</button>
    </div>
</div>