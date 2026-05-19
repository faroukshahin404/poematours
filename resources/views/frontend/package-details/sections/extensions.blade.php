@if(!empty($details['extensions']))
    <div class="package-extensions" aria-labelledby="package-extensions-heading">
            <h2 id="package-extensions-heading" class="package-extensions__heading">{{ __('Extensions') }}</h2>
            <div class="package-extensions__list">
                @foreach($details['extensions'] as $extension)
                    <article class="package-extension-card">
                        <span class="package-extension-card__badge">{{ $extension['duration_badge'] }}</span>
                        <div class="package-extension-card__body">
                            <h3 class="package-extension-card__title">{{ $extension['title'] }}</h3>
                            <p class="package-extension-card__desc">{{ $extension['description'] }}</p>
                            <button
                                type="button"
                                class="package-extension-card__link"
                                data-extension-open
                                data-extension-index="{{ $loop->index }}"
                                aria-haspopup="dialog"
                            >
                                {{ __('View details') }}
                                <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true">
                                    <path d="M10 7l5 5-5 5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        <div class="package-extension-card__media">
                            <img
                                src="{{ asset($extension['image']) }}"
                                alt="{{ $extension['title'] }}"
                                width="320"
                                height="420"
                                loading="lazy"
                            >
                        </div>
                    </article>
                @endforeach
            </div>
    </div>

    <div class="package-extension-modal" data-extension-modal aria-hidden="true">
        <div class="package-extension-modal__backdrop" data-extension-close></div>
        <div
            class="package-extension-modal__dialog"
            role="dialog"
            aria-modal="true"
            aria-labelledby="package-extension-modal-title"
        >
            <button type="button" class="package-extension-modal__close" data-extension-close aria-label="{{ __('Close') }}">&times;</button>
            <div class="package-extension-modal__media">
                <img src="" alt="" data-extension-image width="640" height="900">
            </div>
            <div class="package-extension-modal__content" data-extension-content>
                <span class="package-extension-modal__badge" data-extension-badge></span>
                <h2 id="package-extension-modal-title" class="package-extension-modal__title" data-extension-title></h2>
                <p class="package-extension-modal__summary" data-extension-summary></p>
                <p class="package-extension-modal__pricing" data-extension-pricing></p>
                <p class="package-extension-modal__inclusions" data-extension-inclusions hidden></p>
                <div class="package-extension-modal__itinerary" data-extension-itinerary></div>
            </div>
        </div>
    </div>

    @php
        $extensionsForJs = collect($details['extensions'])->map(function (array $extension): array {
            $extension['image'] = asset($extension['image']);

            return $extension;
        })->values()->all();
    @endphp
    <script type="application/json" id="package-extensions-data">@json($extensionsForJs)</script>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const dataEl = document.getElementById('package-extensions-data');
                const modal = document.querySelector('[data-extension-modal]');
                if (!dataEl || !modal) return;

                let extensions = [];
                try {
                    extensions = JSON.parse(dataEl.textContent || '[]');
                } catch (error) {
                    extensions = [];
                }

                const imageEl = modal.querySelector('[data-extension-image]');
                const badgeEl = modal.querySelector('[data-extension-badge]');
                const titleEl = modal.querySelector('[data-extension-title]');
                const summaryEl = modal.querySelector('[data-extension-summary]');
                const pricingEl = modal.querySelector('[data-extension-pricing]');
                const inclusionsEl = modal.querySelector('[data-extension-inclusions]');
                const contentEl = modal.querySelector('[data-extension-content]');
                const itineraryEl = modal.querySelector('[data-extension-itinerary]');

                function escapeHtml(value) {
                    return String(value)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;');
                }

                function renderItinerary(itineraryGroups) {
                    if (!Array.isArray(itineraryGroups) || !itineraryGroups.length) {
                        return '<p class="package-extension-modal__empty">' + escapeHtml(@json(__('Itinerary details coming soon.'))) + '</p>';
                    }

                    let dayCounter = 1;
                    let html = '';

                    itineraryGroups.forEach(function (group) {
                        html += '<section class="package-extension-modal__group">';
                        html += '<p class="itinerary-location-badge package-extension-modal__location">';
                        html += '<svg class="itinerary-location-badge__icon" viewBox="0 0 24 24" width="14" height="14" aria-hidden="true">';
                        html += '<path d="M12 21.5s-6.25-5.4-6.25-10.25a6.25 6.25 0 1 1 12.5 0c0 4.85-6.25 10.25-6.25 10.25z" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linejoin="round"/>';
                        html += '<circle cx="12" cy="11.25" r="2" fill="none" stroke="currentColor" stroke-width="1.35"/>';
                        html += '</svg>';
                        html += '<span class="itinerary-location-badge__label">' + escapeHtml(String(group.destination || '').toUpperCase()) + '</span>';
                        html += '</p>';

                        (group.days || []).forEach(function (day) {
                            const titleBody = String(day.title || '').replace(/^Day\s*\d+\s*:\s*/i, '') || String(day.title || '');
                            html += '<article class="package-extension-modal__day">';
                            html += '<h3 class="package-extension-modal__day-title">Day ' + dayCounter + '</h3>';
                            html += '<p class="package-extension-modal__day-desc">' + escapeHtml(day.description || '') + '</p>';
                            if (day.hotel) {
                                html += '<p class="package-extension-modal__day-hotel">';
                                html += '<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M4 12v8M4 18h16v2M6 20v2M18 20v2M7 12V8a2 2 0 0 1 2-2h1l2 4v2" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 12h18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round"/></svg>';
                                html += '<span>' + escapeHtml(String(day.hotel).toUpperCase()) + '</span>';
                                html += '</p>';
                            }
                            html += '</article>';
                            dayCounter += 1;
                        });

                        html += '</section>';
                    });

                    return html;
                }

                function openModal(index) {
                    const ext = extensions[index];
                    if (!ext) return;

                    if (imageEl) {
                        imageEl.src = ext.image || '';
                        imageEl.alt = ext.title || '';
                    }
                    if (badgeEl) badgeEl.textContent = ext.duration_badge || '';
                    if (titleEl) titleEl.textContent = ext.title || '';
                    if (summaryEl) summaryEl.textContent = ext.description_full || ext.description || '';

                    const price = Number(ext.price_per_person || 0);
                    const supplement = Number(ext.single_supplement || 0);
                    if (pricingEl) {
                        pricingEl.textContent = 'FROM $' + price.toLocaleString() + ' PER PERSON | $' + supplement.toLocaleString() + ' SINGLE SUPPLEMENT';
                    }

                    if (inclusionsEl) {
                        const note = String(ext.inclusions_text || '').trim();
                        if (note) {
                            inclusionsEl.textContent = note;
                            inclusionsEl.hidden = false;
                        } else {
                            inclusionsEl.textContent = '';
                            inclusionsEl.hidden = true;
                        }
                    }

                    if (itineraryEl) {
                        itineraryEl.innerHTML = renderItinerary(ext.itinerary || []);
                    }

                    if (contentEl) {
                        contentEl.scrollTop = 0;
                    }

                    modal.classList.add('is-open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('package-extension-modal-open');
                }

                function closeModal() {
                    modal.classList.remove('is-open');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('package-extension-modal-open');
                }

                document.querySelectorAll('[data-extension-open]').forEach(function (button) {
                    button.addEventListener('click', function () {
                        const index = Number(button.getAttribute('data-extension-index'));
                        openModal(index);
                    });
                });

                modal.querySelectorAll('[data-extension-close]').forEach(function (el) {
                    el.addEventListener('click', closeModal);
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                        closeModal();
                    }
                });
            });
        </script>
    @endpush
@endif
