<section class="section home-stories">
    <div class="container">
        <header class="home-section-head home-section-head--editorial">
            <p class="home-section-head__eyebrow">{{ $homeStories['eyebrow'] ?? 'Enhance Your Journey' }}</p>
            <h2>{{ $homeStories['title'] ?? 'Add these experiences to your trip' }}</h2>
        </header>
        <div class="home-stories__grid">
            @forelse (($homeStoryBlogs ?? []) as $blog)
                <a
                    href="{{ route('our.journeys.show', $blog['slug']) }}"
                    class="home-stories__card"
                    aria-label="{{ $blog['title'] ?? __('Blog story') }}"
                >
                    <img
                        src="{{ $blog['cover_image'] }}"
                        alt=""
                        loading="lazy"
                        decoding="async"
                    >
                    <div class="home-stories__content">
                        <h3>{{ $blog['title'] ?? '' }}</h3>
                        <p>{{ $blog['excerpt'] ?? '' }}</p>
                    </div>
                </a>
            @empty
                <p class="home-stories__empty">
                    {{ __('No featured stories are available at the moment.') }}
                </p>
            @endforelse
        </div>
    </div>
</section>
