<section class="package-section" id="itinerary">
    @php
        $hotelProfiles = [
            'Nile Palace Cairo' => [
                'description' => 'Nile Palace Cairo combines heritage-inspired interiors with contemporary comfort. Guests enjoy panoramic river views, concierge-led city experiences, and elegant dining that blends Egyptian flavors with international classics.',
                'gallery' => [
                    asset('assets/images/placeholders/pyramids.avif'),
                    asset('assets/images/placeholders/template-1.jpeg'),
                    asset('assets/images/placeholders/nile-2.jpeg'),
                ],
            ],
            'Luxor Riverside Lodge' => [
                'description' => 'Luxor Riverside Lodge is a refined boutique retreat set near the Nile. Spacious suites, tranquil terraces, and bespoke excursions to nearby temples create a deeply immersive Luxor stay.',
                'gallery' => [
                    asset('assets/images/placeholders/nile-1.avif'),
                    asset('assets/images/placeholders/nile-3.jpg'),
                    asset('assets/images/placeholders/template-2.avif'),
                ],
            ],
            'Aswan Serenity Resort' => [
                'description' => 'Aswan Serenity Resort offers a calm, sunlit atmosphere with river-facing accommodations, personalized service, and curated Nubian cultural experiences designed for slow luxury.',
                'gallery' => [
                    asset('assets/images/placeholders/sea-4.webp'),
                    asset('assets/images/placeholders/sea-5.jpg'),
                    asset('assets/images/placeholders/nile-4.webp'),
                ],
            ],
        ];
    @endphp

    <div class="container itinerary-layout">
        <aside class="itinerary-map">
            <h2>Route Map</h2>
            <div class="itinerary-map__viewer" data-map-zoom>
                <img src="{{ asset('assets/images/placeholders/map.avif') }}" alt="Journey itinerary map" data-map-image>
                <div class="itinerary-map__controls itinerary-map__controls--floating">
                    <button type="button" data-map-zoom-in aria-label="Zoom in">+</button>
                    <button type="button" data-map-zoom-out aria-label="Zoom out">-</button>
                </div>
            </div>
        </aside>

        <div class="itinerary-days">
            <h2 class="itinerary-days__heading">Itinerary</h2>
            @php($timelineDay = 1)
            @foreach($details['itinerary'] as $group)
                <section class="itinerary-group" aria-labelledby="itinerary-dest-{{ $loop->index }}">
                    <div class="itinerary-group__head">
                        <p class="itinerary-location-badge" id="itinerary-dest-{{ $loop->index }}">
                            <svg class="itinerary-location-badge__icon" viewBox="0 0 24 24" width="14" height="14" aria-hidden="true">
                                <path d="M12 21.5s-6.25-5.4-6.25-10.25a6.25 6.25 0 1 1 12.5 0c0 4.85-6.25 10.25-6.25 10.25z" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linejoin="round"/>
                                <circle cx="12" cy="11.25" r="2" fill="none" stroke="currentColor" stroke-width="1.35"/>
                            </svg>
                            <span class="itinerary-location-badge__label">{{ strtoupper($group['destination']) }}</span>
                        </p>
                    </div>
                    <div class="itinerary-group__list">
                        @foreach($group['days'] as $day)
                            @php($dayTitleBody = preg_replace('/^Day\s*\d+\s*:\s*/i', '', $day['title']) ?: $day['title'])
                            <article class="itinerary-day">
                                <div class="itinerary-day__content">
                                    <h3 class="itinerary-day__heading">
                                        <span class="itinerary-day__heading-day">Day {{ $timelineDay }}</span>
                                        <span class="itinerary-day__heading-sep" aria-hidden="true">|</span>
                                        <span class="itinerary-day__heading-text">{{ $dayTitleBody }}</span>
                                    </h3>
                                    <p class="itinerary-day__desc">{{ $day['description'] }}</p>
                                    @php($hotelProfile = $hotelProfiles[$day['hotel']] ?? null)
                                    <button
                                        type="button"
                                        class="itinerary-day__hotel"
                                        data-itinerary-hotel-open
                                        data-hotel-name="{{ $day['hotel'] }}"
                                        data-hotel-description="{{ $hotelProfile['description'] ?? 'A curated luxury stay selected for this departure.' }}"
                                        data-hotel-gallery='@json($hotelProfile["gallery"] ?? [asset("assets/images/placeholders/banner.jpeg")])'
                                    >
                                        <span class="itinerary-day__hotel-inner">
                                            <svg class="itinerary-day__hotel-icon" viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                                                <path d="M4 12v8M4 18h16v2M6 20v2M18 20v2M7 12V8a2 2 0 0 1 2-2h1l2 4v2" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M3 12h18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round"/>
                                            </svg>
                                            <span class="itinerary-day__hotel-name">{{ strtoupper($day['hotel']) }}</span>
                                            <svg class="itinerary-day__hotel-chevron" viewBox="0 0 24 24" width="14" height="14" aria-hidden="true">
                                                <path d="M10 7l5 5-5 5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </article>
                            @php($timelineDay++)
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </div>

    <div class="itinerary-hotel-modal" data-itinerary-hotel-modal aria-hidden="true">
        <div class="itinerary-hotel-modal__backdrop" data-itinerary-hotel-close></div>
        <div class="itinerary-hotel-modal__dialog">
            <button type="button" class="itinerary-hotel-modal__close" data-itinerary-hotel-close aria-label="Close hotel modal">&times;</button>
            <div class="itinerary-hotel-modal__left">
                <button type="button" class="itinerary-hotel-modal__main-image" data-itinerary-hotel-main-open aria-label="Open full screen gallery">
                    <img src="" alt="Hotel gallery preview" data-itinerary-hotel-main-image>
                </button>
                <div class="itinerary-hotel-modal__thumbs" data-itinerary-hotel-thumbs></div>
            </div>
            <div class="itinerary-hotel-modal__right">
                <h3 data-itinerary-hotel-title>Hotel</h3>
                <p data-itinerary-hotel-description></p>
            </div>
        </div>
    </div>

    <div class="itinerary-hotel-gallery" data-itinerary-hotel-gallery aria-hidden="true">
        <button type="button" class="itinerary-hotel-gallery__close" data-itinerary-gallery-close aria-label="Close full screen gallery">&times;</button>
        <button type="button" class="itinerary-hotel-gallery__nav itinerary-hotel-gallery__nav--prev" data-itinerary-gallery-prev aria-label="Previous image">&#8249;</button>
        <img src="" alt="Hotel full screen image" data-itinerary-gallery-image>
        <button type="button" class="itinerary-hotel-gallery__nav itinerary-hotel-gallery__nav--next" data-itinerary-gallery-next aria-label="Next image">&#8250;</button>
    </div>
</section>
