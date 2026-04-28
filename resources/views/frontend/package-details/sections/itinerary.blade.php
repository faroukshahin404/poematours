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
            <h2>Itinerary</h2>
            @php($timelineDay = 1)
            @foreach($details['itinerary'] as $group)
                <div class="itinerary-group">
                    <h3>{{ $group['destination'] }}</h3>
                    @foreach($group['days'] as $day)
                        <article class="itinerary-day">
                            <div class="itinerary-day__point" aria-hidden="true">
                                <span>{{ $timelineDay }}</span>
                            </div>
                            <div class="itinerary-day__content">
                                <h4>{{ $day['title'] }}</h4>
                                <p>{{ $day['description'] }}</p>
                                @php($hotelProfile = $hotelProfiles[$day['hotel']] ?? null)
                                <button
                                    type="button"
                                    class="itinerary-day__hotel"
                                    data-itinerary-hotel-open
                                    data-hotel-name="{{ $day['hotel'] }}"
                                    data-hotel-description="{{ $hotelProfile['description'] ?? 'A curated luxury stay selected for this departure.' }}"
                                    data-hotel-gallery='@json($hotelProfile["gallery"] ?? [asset("assets/images/placeholders/banner.jpeg")])'
                                >
                                    {{ $day['hotel'] }}
                                </button>
                            </div>
                        </article>
                        @php($timelineDay++)
                    @endforeach
                </div>
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
