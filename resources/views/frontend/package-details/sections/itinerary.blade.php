<section class="package-section package-section--itinerary" id="itinerary">
    <div class="itinerary-layout itinerary-layout--full-bleed-map">
        <aside class="itinerary-map">
            <div
                class="itinerary-map__viewer"
                data-itinerary-live-map
                data-itinerary-points='@json($details["itinerary_map_points"] ?? [])'
            >
                <div class="itinerary-map__leaflet" data-itinerary-map-canvas></div>
                <div class="itinerary-map__controls itinerary-map__controls--floating">
                    <button type="button" data-itinerary-map-zoom-in aria-label="Zoom in">+</button>
                    <button type="button" data-itinerary-map-zoom-out aria-label="Zoom out">-</button>
                </div>
            </div>
        </aside>

        <div class="itinerary-days-column">
            <div class="container">
                <div class="itinerary-days">
                    <h2 class="itinerary-days__heading">{{ $details['labels']['itinerary'] ?? __('Itinerary') }}</h2>
                    @php($timelineDay = 1)
                    @foreach($details['itinerary'] as $group)
                        <section
                            class="itinerary-group"
                            aria-labelledby="itinerary-dest-{{ $loop->index }}"
                            data-itinerary-group
                            data-itinerary-group-index="{{ $group['map_index'] ?? $loop->index }}"
                        >
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
                                            <button
                                                type="button"
                                                class="itinerary-day__hotel"
                                                data-itinerary-hotel-open
                                                data-hotel-name="{{ $day['hotel'] }}"
                                                data-hotel-description="{{ $day['hotel_description'] ?? 'A curated luxury stay selected for this departure.' }}"
                                                data-hotel-gallery='@json(collect($day["hotel_gallery"] ?? ["assets/images/placeholders/banner.jpeg"])->map(fn ($img) => asset($img))->values()->all())'
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

@push('styles')
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    >
    <style>
        .itinerary-map__leaflet {
            width: 100%;
            height: 100%;
            min-height: 0;
        }

        .itinerary-map-dot-label {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #2b2b2b;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 6px;
            box-shadow: none;
        }

        .itinerary-map-dot-label::before {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
    ></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mapRoot = document.querySelector('[data-itinerary-live-map]');
            if (!mapRoot || typeof L === 'undefined') {
                return;
            }

            let points = [];
            try {
                points = JSON.parse(mapRoot.dataset.itineraryPoints || '[]');
            } catch (error) {
                points = [];
            }

            const canvas = mapRoot.querySelector('[data-itinerary-map-canvas]');
            if (!canvas) return;

            const map = L.map(canvas, { zoomControl: false }).setView([26.8206, 30.8025], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            const pointMap = new Map();
            const latLngs = [];

            points.forEach((point) => {
                const latLng = [Number(point.lat), Number(point.lng)];
                latLngs.push(latLng);

                const dot = L.circleMarker(latLng, {
                    radius: 6,
                    color: '#1f1f1f',
                    weight: 1,
                    fillColor: '#313131',
                    fillOpacity: 0.95,
                }).addTo(map);

                dot.bindTooltip(point.name, {
                    permanent: true,
                    direction: 'bottom',
                    offset: [0, 10],
                    className: 'itinerary-map-dot-label',
                });

                pointMap.set(String(point.index), latLng);
            });

            if (latLngs.length > 1) {
                L.polyline(latLngs, {
                    color: '#606060',
                    weight: 2,
                    opacity: 0.85,
                    dashArray: '2 8',
                }).addTo(map);
            }

            if (latLngs.length) {
                map.fitBounds(latLngs, { padding: [30, 30] });
            }

            requestAnimationFrame(() => {
                map.invalidateSize();
                if (latLngs.length) {
                    map.fitBounds(latLngs, { padding: [30, 30] });
                }
            });

            const zoomInBtn = mapRoot.querySelector('[data-itinerary-map-zoom-in]');
            const zoomOutBtn = mapRoot.querySelector('[data-itinerary-map-zoom-out]');
            if (zoomInBtn) zoomInBtn.addEventListener('click', () => map.zoomIn());
            if (zoomOutBtn) zoomOutBtn.addEventListener('click', () => map.zoomOut());

            const groups = Array.from(document.querySelectorAll('[data-itinerary-group]'));
            if ('IntersectionObserver' in window && groups.length) {
                const observer = new IntersectionObserver((entries) => {
                    const visible = entries
                        .filter((entry) => entry.isIntersecting)
                        .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];
                    if (!visible) return;

                    const index = visible.target.getAttribute('data-itinerary-group-index');
                    const latLng = pointMap.get(String(index));
                    if (!latLng) return;
                    map.flyTo(latLng, Math.max(map.getZoom(), 8), { duration: 0.8 });
                }, {
                    root: null,
                    rootMargin: '-35% 0px -35% 0px',
                    threshold: [0.25, 0.5, 0.75],
                });

                groups.forEach((group) => observer.observe(group));
            }
        });
    </script>
@endpush
