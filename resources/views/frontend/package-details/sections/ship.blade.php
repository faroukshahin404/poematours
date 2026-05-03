@php
    $shipSlides = $details['ship']['gallery'] ?? [$details['ship']['image']];
@endphp

<section class="package-section package-ship-section" id="ship">
    <div class="container package-ship">
        <div class="package-ship__media">
            <div
                class="package-ship__slider{{ count($shipSlides) < 2 ? ' package-ship__slider--single' : '' }}"
                data-ship-slider
            >
                @foreach ($shipSlides as $slideSrc)
                    <figure
                        class="package-ship__slide{{ $loop->first ? ' is-active' : '' }}"
                        data-ship-slide
                    >
                        <img
                            class="package-ship__img"
                            src="{{ asset($slideSrc) }}"
                            alt="{{ $details['ship']['name'] }} — gallery image {{ $loop->iteration }}"
                            @if (! $loop->first)
                                loading="lazy"
                                decoding="async"
                            @endif
                        >
                    </figure>
                @endforeach
                <button
                    type="button"
                    class="package-ship__nav package-ship__nav--prev"
                    data-ship-prev
                    aria-label="Previous ship image"
                >
                    <svg class="package-ship__nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M14.5 6.5L9 12l5.5 5.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <button
                    type="button"
                    class="package-ship__nav package-ship__nav--next"
                    data-ship-next
                    aria-label="Next ship image"
                >
                    <svg class="package-ship__nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M9.5 6.5L15 12l-5.5 5.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="package-ship__panel">
            <p class="package-ship__eyebrow">About the ship</p>
            <h2 class="package-ship__title">{{ $details['ship']['name'] }}</h2>
            <p class="package-ship__desc">{{ $details['ship']['description'] }}</p>
        </div>
    </div>
</section>
