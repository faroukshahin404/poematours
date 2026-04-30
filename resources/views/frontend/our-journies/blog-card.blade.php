<article class="journey-card" data-journey-card data-category="{{ strtolower($blog['category']) }}" data-journey-animate="card">
    <img src="{{ asset($blog['cover_image']) }}" alt="{{ $blog['title'] }}">
    <div class="journey-card__content">
        <span class="journeys-badge">{{ $blog['category'] }}</span>
        <h3>{{ $blog['title'] }}</h3>
        <p>{{ $blog['excerpt'] }}</p>
        <a href="{{ route('our.journeys.show', $blog['slug']) }}" class="journey-card__link">Read Journal</a>
    </div>
</article>