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
            <p class="package-ship__eyebrow">{{ $details['labels']['ship'] ?? __('About the ship') }}</p>
            <h2 class="package-ship__title">{{ $details['ship']['name'] }}</h2>
            <div class="package-ship__desc-wrap" data-ship-desc>
                <div
                    class="package-ship__desc-inner package-ship__desc-inner--clamped"
                    data-ship-desc-content
                >
                    {!! $details['ship']['description'] !!}
                </div>
                <button
                    type="button"
                    class="package-ship__read-more"
                    data-ship-desc-toggle
                    hidden
                    aria-expanded="false"
                    data-label-more="{{ __('Read more') }}"
                    data-label-less="{{ __('Read less') }}"
                >
                    {{ __('Read more') }}
                </button>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        (function () {
            function initShipDesc() {
                document.querySelectorAll('[data-ship-desc]').forEach(function (wrap) {
                    const content = wrap.querySelector('[data-ship-desc-content]');
                    const btn = wrap.querySelector('[data-ship-desc-toggle]');
                    if (!content || !btn) {
                        return;
                    }

                    const labelMore = btn.getAttribute('data-label-more') || 'Read more';
                    const labelLess = btn.getAttribute('data-label-less') || 'Read less';

                    function isExpanded() {
                        return content.classList.contains('is-expanded');
                    }

                    function updateToggleVisibility() {
                        if (isExpanded()) {
                            btn.hidden = false;
                            return;
                        }
                        content.classList.add('package-ship__desc-inner--clamped');
                        void content.offsetHeight;
                        const overflow = content.scrollHeight - content.clientHeight > 2;
                        btn.hidden = !overflow;
                        if (!overflow) {
                            btn.setAttribute('aria-expanded', 'false');
                            btn.textContent = labelMore;
                        }
                    }

                    btn.addEventListener('click', function () {
                        if (isExpanded()) {
                            content.classList.remove('is-expanded');
                            content.classList.add('package-ship__desc-inner--clamped');
                            btn.setAttribute('aria-expanded', 'false');
                            btn.textContent = labelMore;
                        } else {
                            content.classList.add('is-expanded');
                            content.classList.remove('package-ship__desc-inner--clamped');
                            btn.setAttribute('aria-expanded', 'true');
                            btn.textContent = labelLess;
                        }
                        btn.hidden = false;
                    });

                    updateToggleVisibility();
                    window.addEventListener('resize', function () {
                        if (!isExpanded()) {
                            updateToggleVisibility();
                        }
                    });
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initShipDesc);
            } else {
                initShipDesc();
            }
        })();
    </script>
@endpush
