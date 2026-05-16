<section class="section experiences-section home-destinations">
    <div class="container">
        <header class="home-section-head home-section-head--editorial">
            <p class="home-section-head__eyebrow">{{ __('Journeys Through Time') }}</p>
            <h2>{{ __('Destinations that move your heart before your camera.') }}</h2>
            <p class="home-section-head__lede">{{ __('Handpicked regions, curated with care — each journey shaped around the rhythm of Egypt.') }}</p>
        </header>
        <div class="experiences-section__grid">
            @forelse ($homeDestinations ?? collect() as $destination)
                @php
                    $experience = [
                        'title' => $destination->name,
                        'location' => $destination->name.', Egypt',
                        'slug' => $destination->slug,
                        'image' => $destination->getRawOriginal('image')
                            ? 'storage/'.$destination->getRawOriginal('image')
                            : 'assets/images/placeholders/banner.jpeg',
                        'description' => __('Discover curated journeys and handpicked experiences across :destination.', ['destination' => $destination->name]),
                    ];
                @endphp
                @include('frontend.packages.cards.experience-destination-card', ['experience' => $experience])
            @empty
                <p class="experiences-section__empty">{{ __('No destinations are available at the moment.') }}</p>
            @endforelse
        </div>
    </div>
</section>
