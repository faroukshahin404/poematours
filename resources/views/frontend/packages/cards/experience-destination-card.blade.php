
<article class="experience-card">
    <div class="experience-card__media">
        <img src="{{ asset($experience['image']) }}" alt="{{ $experience['title'] }}">
    </div>
    <div class="experience-card__body">
        <p class="experience-card__location">{{ $experience['location'] }}</p>
        <h3>{{ $experience['title'] }}</h3>
        <a href="{{ route('packages.index', ['destination' => $experience['slug']]) }}" class="experience-card__button">
            {{ __('Discover') }}
        </a>
    </div>
</article>
