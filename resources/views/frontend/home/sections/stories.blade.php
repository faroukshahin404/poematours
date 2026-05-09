<section class="section home-stories">
    <div class="container">
        <div class="home-section-head">
            <p>{{ $homeStories['eyebrow'] ?? 'Enhance Your Journey' }}</p>
            <h2>{{ $homeStories['title'] ?? 'Add these experiences to your trip' }}</h2>
        </div>
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
                <p class="home-stories__empty" style="grid-column: 1 / -1; margin: 0; color: #5c6975;">
                    {{ __('No featured stories are available at the moment.') }}
                </p>
            @endforelse
        </div>
    </div>
</section>
