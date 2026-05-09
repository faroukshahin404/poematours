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
            data-accommodation-images="{{ asset($accommodation['image']) }}|{{ asset($accommodation['image']) }}|{{ asset($accommodation['image']) }}"
            data-accommodation-description="Located on the legendary Corniche Nile River in the heart of Cairo, this handpicked stay pairs timeless Egyptian hospitality with refined comfort. Thoughtfully designed spaces invite you to unwind after your journey, while curated details reflect the character of the destination. Whether you are resting between excursions or settling in for longer, the experience is tailored to feel personal, calm, and unmistakably yours."
        >
            View Details
        </button>
    </div>
</article>
