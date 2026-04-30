<article class="experience-card">
    <img src="{{ asset($experience['image']) }}" alt="{{ $experience['title'] }}">
    <div class="experience-card__body">
        <p class="experience-card__location">{{ $experience['location'] }}</p>
        <h3>{{ $experience['title'] }}</h3>
        <button
            type="button"
            class="experience-card__button"
            data-experience-open
            data-experience-image="{{ asset($experience['image']) }}"
            data-experience-title="{{ $experience['title'] }}"
            data-experience-description="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur."
        >
            Learn More
        </button>
    </div>
</article>
