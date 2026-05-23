<section class=" about-reels" style="margin-bottom: 10px;">
    <div class="container">
        <h2 class="section-title">Reels</h2>
        <div class="about-reels__viewport">
            <div class="about-reels__track" data-reels-track>
                @forelse (($reels ?? []) as $reel)
                    <article class="reel-card" data-reel-open data-video-src="{{ $reel['video'] ?? '' }}">
                        <img src="{{ $reel['snapshot'] ?? asset('assets/images/placeholders/banner.jpeg') }}" alt="{{ $reel['title'] ?? 'Reel snapshot' }} snapshot">
                        <span class="reel-card__play" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="presentation" focusable="false">
                                <path d="M8 6.5v11l9-5.5-9-5.5z" fill="currentColor"></path>
                            </svg>
                        </span>
                        <div class="reel-card__meta">
                            <h3>{{ $reel['title'] ?? 'Reel' }}</h3>
                            {{-- description is html content --}}
                            <p>{!! $reel['description'] ?? '' !!}</p>
                        </div>
                    </article>
                @empty
                    <p>No reels available right now.</p>
                @endforelse
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
