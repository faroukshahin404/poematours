<aside class="packages-filters">
    <form action="{{ route('packages.index') }}" method="GET">
        <div class="packages-filters__group packages-filters__group--regions">
            <h3>Regions</h3>
            <div class="packages-filters__regions-box">
                <label class="packages-filters__region-option">
                    <input type="radio" name="destination" value="cairo" @checked(request('destination') === 'cairo')>
                    <span>Cairo</span>
                </label>
                <label class="packages-filters__region-option">
                    <input type="radio" name="destination" value="luxor" @checked(request('destination') === 'luxor')>
                    <span>Luxor</span>
                </label>
            </div>
        </div>

        <div class="packages-filters__group">
            <h3>Travel Style</h3>
            <div class="packages-filters__chips">
                <label class="packages-filters__chip">
                    <input type="checkbox" name="activity_types[]" value="small-group" @checked(in_array('small-group', (array) request('activity_types', []), true))>
                    <span>Join A Group</span>
                </label>
                <label class="packages-filters__chip">
                    <input type="checkbox" name="activity_types[]" value="private" @checked(in_array('private', (array) request('activity_types', []), true))>
                    <span>Travel Privately</span>
                </label>
            </div>
        </div>

        <div class="packages-filters__group">
            <h3>Ways to Travel</h3>
            <div class="packages-filters__chips">
                @foreach(['Private Ready-To-Book', 'Small Group Journeys', 'Small Jet Journeys', 'Tailormade Journeys'] as $way)
                    <label class="packages-filters__chip">
                        <input type="checkbox" name="travel_ways[]" value="{{ strtolower(str_replace(' ', '-', $way)) }}">
                        <span>{{ $way }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="packages-filters__group">
            <h3>Interests</h3>
            <div class="packages-filters__chips">
                @foreach(['A&K Sanctuary', 'Adventure', 'City', 'Cruises', 'Culture & History', 'En Espanol', 'Family', 'Jet-Set', 'River Cruise', 'Short Escapes', 'Solo'] as $interest)
                    <label class="packages-filters__chip">
                        <input type="checkbox" name="interests[]" value="{{ strtolower(str_replace(' ', '-', $interest)) }}">
                        <span>{{ $interest }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="packages-filters__group packages-filters__group--compact">
            <h3>Travel Date</h3>
            <input id="travel_date_filter" type="date" name="travel_date" value="{{ request('travel_date') }}">
        </div>

        <div class="packages-filters__group packages-filters__group--compact">
            <h3>Duration</h3>
            <div class="packages-filters__chips">
                @foreach(['4' => 'Up to 4 days', '6' => 'Up to 6 days', '9' => 'Up to 9 days'] as $value => $label)
                    <label class="packages-filters__chip">
                        <input type="radio" name="duration" value="{{ $value }}" @checked((string) request('duration') === $value)>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="packages-filters__group packages-filters__group--compact">
            <h3>Price Range</h3>
            <div class="packages-filters__price">
                @php($selectedMaxPrice = (int) request('price_max', 4000))
                <input id="price_max_filter" type="range" name="price_max" min="500" max="5000" step="100" value="{{ $selectedMaxPrice }}" data-price-range data-price-output-id="priceRangeValue">
                <div class="packages-filters__range-indicator">
                    <span>Up to</span>
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
