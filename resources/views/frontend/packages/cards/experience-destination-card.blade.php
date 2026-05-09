
<article class="experience-card">
    <img src="{{ asset($experience['image']) }}" alt="{{ $experience['title'] }}">
    <div class="experience-card__body">
        <p class="experience-card__location">{{ $experience['location'] }}</p>
        <h3>{{ $experience['title'] }}</h3>
        <a href="{{ route('packages.index', ['destination' => $experience['slug']]) }}" class="experience-card__button">
            Learn More
        </a>
    </div>
</article>
