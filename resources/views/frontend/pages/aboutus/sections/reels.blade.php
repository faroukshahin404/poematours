<section class=" about-reels" style="margin-bottom: 10px;">
    <div class="container">
        <h2 class="section-title">Reels</h2>
        @php
            $reels = [
                ['video' => asset('assets/videos/placeholders/video-1.mp4'), 'snapshot' => asset('assets/images/placeholders/nile-2.jpeg'), 'title' => 'Nile Sunset Moments', 'description' => 'A glimpse into elegant evenings on the Nile.'],
                ['video' => asset('assets/videos/placeholders/video-2.mp4'), 'snapshot' => asset('assets/images/placeholders/pyramids.avif'), 'title' => 'Pyramids Experience', 'description' => 'Iconic desert heritage through a modern travel lens.'],
                ['video' => asset('assets/videos/placeholders/video-3.mp4'), 'snapshot' => asset('assets/images/placeholders/sea-2.jpg'), 'title' => 'Red Sea Escape', 'description' => 'Premium coastal relaxation and vibrant scenery.'],
                ['video' => asset('assets/videos/placeholders/video-1.mp4'), 'snapshot' => asset('assets/images/placeholders/nile-2.jpeg'), 'title' => 'Nile Sunset Moments', 'description' => 'A glimpse into elegant evenings on the Nile.'],
                ['video' => asset('assets/videos/placeholders/video-2.mp4'), 'snapshot' => asset('assets/images/placeholders/pyramids.avif'), 'title' => 'Pyramids Experience', 'description' => 'Iconic desert heritage through a modern travel lens.'],
                ['video' => asset('assets/videos/placeholders/video-3.mp4'), 'snapshot' => asset('assets/images/placeholders/sea-2.jpg'), 'title' => 'Red Sea Escape', 'description' => 'Premium coastal relaxation and vibrant scenery.'],
            ];
        @endphp
        <div class="about-reels__viewport">
            <div class="about-reels__track" data-reels-track>
                @foreach ($reels as $reel)
                    <article class="reel-card" data-reel-open data-video-src="{{ $reel['video'] }}">
                        <img src="{{ $reel['snapshot'] }}" alt="{{ $reel['title'] }} snapshot">
                        <span class="reel-card__play" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="presentation" focusable="false">
                                <path d="M8 6.5v11l9-5.5-9-5.5z" fill="currentColor"></path>
                            </svg>
                        </span>
                        <div class="reel-card__meta">
                            <h3>{{ $reel['title'] }}</h3>
                            <p>{{ $reel['description'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
<div class="reel-modal" data-reel-modal aria-hidden="true">
    <div class="reel-modal__backdrop" data-reel-close></div>
    <div class="reel-modal__dialog">
        <button type="button" class="reel-modal__close" data-reel-close aria-label="Close reel">&times;</button>
        <video controls playsinline data-reel-video></video>
    </div>
</div>
