<section class="section experiences-section">
    <div class="container">
        <div class="experiences-section__head">
            <p>Journeys Through Time</p>
            <h2>Destinations that move your heart before your camera.</h2>
        </div>
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
                <p class="experiences-section__empty" style="grid-column: 1 / -1; margin: 0; color: #5c6975;">
                    No destinations are available at the moment.
                </p>
            @endforelse
        </div>
    </div>
</section>
