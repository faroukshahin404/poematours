<aside class="packages-filters">
    <form action="{{ route('packages.index') }}" method="GET">
        <h2>Filters</h2>

        <div class="packages-filters__group">
            <label for="destination_filter">Destination</label>
            <select id="destination_filter" name="destination">
                <option value="">All</option>
                <option value="cairo" @selected(request('destination') === 'cairo')>Cairo</option>
                <option value="aswan" @selected(request('destination') === 'aswan')>Aswan</option>
                <option value="luxor" @selected(request('destination') === 'luxor')>Luxor</option>
            </select>
        </div>

        <div class="packages-filters__group">
            <label for="travel_date_filter">Date</label>
            <input id="travel_date_filter" type="date" name="travel_date" value="{{ request('travel_date') }}">
        </div>

        <div class="packages-filters__group">
            <label for="duration_filter">Duration (Days)</label>
            <select id="duration_filter" name="duration">
                <option value="">Any</option>
                <option value="4" @selected((string) request('duration') === '4')>Up to 4 days</option>
                <option value="6" @selected((string) request('duration') === '6')>Up to 6 days</option>
                <option value="9" @selected((string) request('duration') === '9')>Up to 9 days</option>
            </select>
        </div>

        <div class="packages-filters__group">
            <p>Activity Types</p>
            @php($selectedActivities = (array) request('activity_types', []))
            @foreach(['culture' => 'Culture', 'cycling' => 'Cycling', 'family' => 'Family', 'polar' => 'Polar'] as $value => $label)
                <label class="packages-filters__checkbox">
                    <input type="checkbox" name="activity_types[]" value="{{ $value }}" @checked(in_array($value, $selectedActivities, true))>
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>

        <div class="packages-filters__group">
            <p>Price Range</p>
            <div class="packages-filters__price">
                @php($selectedMaxPrice = (int) request('price_max', 4000))
                <input
                    id="price_max_filter"
                    type="range"
                    name="price_max"
                    min="500"
                    max="5000"
                    step="100"
                    value="{{ $selectedMaxPrice }}"
                    data-price-range
                    data-price-output-id="priceRangeValue"
                >
                <div class="packages-filters__range-indicator">
                    <span>Up to:</span>
                    <strong id="priceRangeValue">${{ number_format($selectedMaxPrice) }}</strong>
                </div>
            </div>
        </div>

        <input type="hidden" name="adults" value="{{ request('adults', 2) }}">
        <input type="hidden" name="kids" value="{{ request('kids', 0) }}">
        <input type="hidden" name="view" value="{{ request('view', 'grid') }}">

        <button class="packages-filters__submit" type="submit">Apply Filters</button>
    </form>
</aside>
