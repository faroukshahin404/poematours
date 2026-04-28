<section class="package-section" id="dates-prices">
    <div class="container dates-prices-layout">
        <aside class="dates-prices-info">
            <h2>Inclusions &amp; Offers</h2>

            <div class="dates-prices-offers">
                @foreach($details['dates_prices']['offer_cards'] as $offer)
                    <article class="dates-prices-offer-card">
                        <p class="dates-prices-offer-card__title">{{ $offer['title'] }}</p>
                        <p class="dates-prices-offer-card__description">{{ $offer['description'] }}</p>
                        <a href="#">{{ $offer['link_label'] }}</a>
                    </article>
                @endforeach
            </div>

            <div class="dates-prices-info__columns dates-prices-info__columns--single">
                <div>
                    <ul class="dates-prices-info__list">
                        @foreach($details['dates_prices']['inclusions'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="dates-prices-notes">
                    @foreach($details['dates_prices']['notes'] as $note)
                        <div class="dates-prices-note">{{ $note }}</div>
                    @endforeach
                </div>
            </div>
        </aside>

        <div class="dates-prices-table">
            <div class="dates-prices-table__head">
                <div>
                    <h3>Dates &amp; Prices</h3>
                    <p>Choose a date to view available suites</p>
                </div>
                <div class="dates-prices-table__actions">
                    <form method="GET" action="{{ route('packages.show', $package['slug']) }}">
                        <label for="yearFilter" class="sr-only">Filter by year</label>
                        <select id="yearFilter" name="year" onchange="this.form.submit()">
                            <option value="2026" @selected($selectedYear === 2026)>2026</option>
                            <option value="2027" @selected($selectedYear === 2027)>2027</option>
                        </select>
                    </form>
                    <label for="adultsCount" class="sr-only">Adults count</label>
                    <select id="adultsCount" data-booking-adults>
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" @selected(($selectedAdults ?? 2) === $i)>{{ $i }} Adult{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="dates-prices-accordion" data-month-accordion>
                @foreach($details['dates_prices']['months'] as $month => $monthData)
                    <div class="dates-prices-month {{ $loop->first ? 'is-open' : '' }}" data-month-item>
                        <button type="button" class="dates-prices-month__summary" data-month-toggle>
                            <span class="dates-prices-month__name">
                                {{ $month }}
                                @if($monthData['has_offer'])
                                    <em>Offer</em>
                                @endif
                            </span>
                            <span class="dates-prices-month__price">From ${{ number_format($monthData['from_price']) }}</span>
                            <span class="dates-prices-month__badge">{{ $monthData['availability'] }}</span>
                            <span class="dates-prices-month__chevron">&#8964;</span>
                        </button>

                        <div class="dates-prices-month__content">
                            @foreach($monthData['periods'] as $period)
                                <div class="dates-prices-row">
                                    <label class="dates-prices-row__radio" aria-label="Select {{ $period['period'] }}">
                                        <input
                                            type="radio"
                                            name="departure_date"
                                            value="{{ $period['id'] }}"
                                            data-departure-radio
                                            data-id="{{ $period['id'] }}"
                                            data-from-date="{{ $period['from_date'] }}"
                                            data-to-date="{{ $period['to_date'] }}"
                                            data-price="{{ $period['price'] }}"
                                            data-available-spaces="{{ $period['available_spaces'] }}"
                                            data-single-supplement="{{ $period['single_supplement'] }}"
                                            data-availability="{{ $period['availability'] }}"
                                            @checked($loop->first && $loop->parent->first)
                                        >
                                    </label>
                                    <span>{{ strtoupper($period['period']) }}</span>
                                    <strong>From ${{ number_format($period['price']) }}</strong>
                                    <button
                                        type="button"
                                        data-hotel-open
                                        data-title="{{ $month }} Departure"
                                        data-image="{{ asset($period['hotel_image']) }}"
                                        data-description="{{ $period['hotel_description'] }}"
                                        data-price="${{ number_format($period['price']) }}"
                                        data-supplement="${{ number_format($period['single_supplement']) }}"
                                        data-cabin="{{ $period['cabin'] }}"
                                    >
                                        View Suites
                                    </button>
                                    <span class="dates-prices-row__availability">{{ $period['availability'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="booking-selection-card" data-booking-selection>
                <div class="booking-selection-card__meta">
                    <h4 data-booking-price>From $0</h4>
                    <p><strong>From:</strong> <span data-booking-from>-</span></p>
                    <p><strong>To:</strong> <span data-booking-to>-</span></p>
                    <p><strong>Available spaces:</strong> <span data-booking-spaces>-</span></p>
                    <p><strong>Single supplement:</strong> <span data-booking-supplement>-</span></p>
                    <p><strong>Adults:</strong> <span data-booking-adults-text>{{ $selectedAdults ?? 2 }}</span></p>
                </div>
                <a href="#" class="booking-selection-card__cta" data-booking-cta>Book This Trip</a>
            </div>
        </div>
    </div>
</section>
