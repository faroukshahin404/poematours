<section class="hero">
    <div class="hero__slides">
        <div class="hero__slide is-active">
            <img
                class="hero__background"
                src="{{ asset($homeHero['background_image'] ?? 'assets/images/placeholders/banner.jpeg') }}"
                alt="{{ $homeHero['background_image_alt'] ?? 'Golden sunrise over Egypt landscapes' }}"
            >
        </div>
    </div>
    <div class="hero__overlay"></div>

    @php
        $heroTitleBefore = trim((string) ($homeHero['title_before'] ?? "We'll take care of"));
        $heroTitleHighlight = trim((string) ($homeHero['title_highlight'] ?? 'everything.'));
        $heroTitleAfter = trim((string) ($homeHero['title_after'] ?? ''));

        if ($heroTitleBefore === '' && $heroTitleHighlight === '' && ! empty($homeHero['title'])) {
            $heroTitleBefore = (string) $homeHero['title'];
        }
    @endphp

    <div class="hero__stage">
        <div class="container hero__content hero__content--centered">
            <p class="hero__eyebrow home-hero-animate" style="--home-hero-delay: 120ms;">{{ $homeHero['eyebrow'] ?? 'Experience Delight.' }}</p>
            <h1 class="hero__title home-hero-animate" style="--home-hero-delay: 260ms;">
                @if($heroTitleBefore !== '')
                    <span class="hero__title-part">{{ $heroTitleBefore }}</span>
                @endif
                @if($heroTitleHighlight !== '')
                    <span class="hero__title-accent">{{ $heroTitleHighlight }}</span>
                @endif
                @if($heroTitleAfter !== '')
                    <span class="hero__title-part">{{ $heroTitleAfter }}</span>
                @endif
            </h1>
            <a href="{{ url($homeHero['cta_url'] ?? '/packages') }}" class="hero__cta home-hero-animate" style="--home-hero-delay: 400ms;">
                {{ $homeHero['cta_text'] ?? 'Design Your Tour' }}
            </a>
        </div>

        <a href="#home-spirit" class="hero__scroll-cue home-hero-animate" style="--home-hero-delay: 520ms;" aria-label="{{ __('Scroll to explore') }}">
            <span class="hero__scroll-cue-text">{{ __('Scroll Down') }}</span>
            <span class="hero__scroll-cue-arrow" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5v14M6 13l6 6 6-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </a>
    </div>

    <div class="hero__trust-bar home-hero-animate" style="--home-hero-delay: 560ms;" role="region" aria-label="Why travel with Poema Tours">
        <div class="container">
            <ul class="hero__trust-grid">
                @foreach (($homeHero['trust_items'] ?? []) as $item)
                    <li class="hero__trust-item home-hero-animate" style="--home-hero-delay: {{ 640 + ($loop->index * 90) }}ms;">
                        <span class="hero__trust-icon" aria-hidden="true">
                            <svg class="hero__trust-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2.75 5.25 6.2v5.35c0 3.85 2.35 7.45 6.75 9.05 4.4-1.6 6.75-5.2 6.75-9.05V6.2L12 2.75Z" stroke="currentColor" stroke-width="1.35" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <div class="hero__trust-copy">
                            <span class="hero__trust-line">{{ $item['line_1'] ?? '' }}</span>
                            <span class="hero__trust-line">{{ $item['line_2'] ?? '' }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

@push('scripts')
@endpush
