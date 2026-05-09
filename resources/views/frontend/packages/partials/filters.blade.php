<aside class="packages-filters">
    <form action="{{ isset($filtersFormAction) ? $filtersFormAction : route('packages.index') }}" method="GET">
        <div class="packages-filters__group packages-filters__group--regions">
            <h3>Regions</h3>
            <div class="packages-filters__chips">
                @foreach(($destinations ?? collect()) as $destination)
                    <label class="packages-filters__chip packages-filters__chip--destination">
                        <input type="radio" name="destination" value="{{ $destination['slug'] }}" @checked(($filters['destination'] ?? '') === $destination['slug'])>
                        <span>{{ $destination['name'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="packages-filters__group">
            <h3>Categories</h3>
            <div class="packages-filters__chips">
                @foreach(($categories ?? collect()) as $category)
                    <label class="packages-filters__chip">
                        <input type="checkbox" name="category_ids[]" value="{{ $category['id'] }}" @checked(in_array((int) $category['id'], (array) ($filters['category_ids'] ?? []), true))>
                        <span>{{ $category['name'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        @foreach(($labelGroups ?? collect()) as $group)
            <div class="packages-filters__group">
                <h3>{{ $group['name'] }}</h3>
                <div class="packages-filters__chips">
                    @foreach(($group['labels'] ?? []) as $label)
                        <label class="packages-filters__chip">
                            <input type="checkbox" name="label_ids[]" value="{{ $label['id'] }}" @checked(in_array((int) $label['id'], (array) ($filters['label_ids'] ?? []), true))>
                            <span>{{ $label['name'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="packages-filters__group packages-filters__group--compact">
            <h3>Travel Period</h3>
            <div class="grid gap-2">
                <input id="from_date_filter" type="date" name="from_date" value="{{ $filters['from_date'] ?? '' }}">
                <input id="to_date_filter" type="date" name="to_date" value="{{ $filters['to_date'] ?? '' }}">
            </div>
        </div>

        <div class="packages-filters__group packages-filters__group--compact">
            <h3>Duration</h3>
            <div class="packages-filters__chips">
                @foreach(['4' => 'Up to 4 days', '6' => 'Up to 6 days', '9' => 'Up to 9 days'] as $value => $label)
                    <label class="packages-filters__chip">
                        <input type="radio" name="duration" value="{{ $value }}" @checked((string) ($filters['duration'] ?? '') === $value)>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="packages-filters__group packages-filters__group--compact">
            <h3>Price Range</h3>
            <div class="packages-filters__price">
                @php($selectedMaxPrice = (int) ($filters['price_max'] ?? ($priceBounds['max'] ?? 5000)))
                @php($minPriceBound = max(0, (int) ($priceBounds['min'] ?? 0)))
                @php($maxPriceBound = max($minPriceBound, (int) ($priceBounds['max'] ?? 5000)))
                <input id="price_max_filter" type="range" name="price_max" min="{{ $minPriceBound }}" max="{{ $maxPriceBound }}" step="100" value="{{ $selectedMaxPrice }}" data-price-range data-price-output-id="priceRangeValue">
                <div class="packages-filters__range-indicator">
                    <span>Up to</span>
                    <strong id="priceRangeValue">${{ number_format($selectedMaxPrice) }}</strong>
                </div>
            </div>
        </div>

        <input type="hidden" name="adults" value="{{ $filters['adults'] ?? request('adults', 2) }}">
        <input type="hidden" name="kids" value="{{ $filters['kids'] ?? request('kids', 0) }}">
        <input type="hidden" name="view" value="{{ $filters['view'] ?? request('view', 'grid') }}">
        @if(in_array($filters['trip_type'] ?? '', ['private', 'small-group'], true))
            <input type="hidden" name="trip_type" value="{{ $filters['trip_type'] }}">
        @endif
        @if(! empty(trim((string) ($filters['q'] ?? ''))))
            <input type="hidden" name="q" value="{{ $filters['q'] }}">
        @endif

        <button class="packages-filters__submit" type="submit">Apply Filters</button>
    </form>
</aside>
