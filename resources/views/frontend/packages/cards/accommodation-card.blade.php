<article class="accommodation-card">
    <div class="accommodation-card__media">
        <img src="{{ asset($accommodation['image']) }}" alt="{{ $accommodation['title'] }}">
        <span class="accommodation-card__badge">A&K Sanctuary</span>
    </div>
    <div class="accommodation-card__body">
        <p class="accommodation-card__location">{{ $accommodation['location'] }}</p>
        <h3>{{ $accommodation['title'] }}</h3>
        <button
            type="button"
            class="accommodation-card__button"
            data-accommodation-open
            data-accommodation-title="{{ $accommodation['title'] }}"
            data-accommodation-image="{{ asset($accommodation['image']) }}"
            data-accommodation-boat-title="The finest Egyptian materials meet modern comfort"
            data-accommodation-boat-description="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat."
            data-accommodation-cabins-title="Cabins and suites crafted for serene comfort"
            data-accommodation-cabins-description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat."
            data-accommodation-food-title="Locally inspired cuisine with modern presentation"
            data-accommodation-food-description="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur."
        >
            View Details
        </button>
    </div>
</article>
