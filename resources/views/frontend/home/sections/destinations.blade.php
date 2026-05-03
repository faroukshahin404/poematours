<section class="section">
    <div class="container">
        <p style="margin: 0; color: #9a6c17; letter-spacing: 0.09em; text-transform: uppercase; font-weight: 600; font-size: 0.8rem;">Journeys Through Time</p>
        <h2 class="section-title">Destinations that move your heart before your camera.</h2>
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-top: 1.5rem;">
            @forelse ($homeDestinations ?? collect() as $destination)
                <article class="card">
                    <img
                        src="{{ $destination->imagePublicUrl() ?? asset('assets/images/placeholders/banner.jpeg') }}"
                        alt="{{ $destination->name }}"
                        style="height: 230px; width: 100%; object-fit: cover;"
                    >
                    <div style="padding: 1rem 1.1rem;">
                        <h3 style="margin: 0; color: #0b3c69;">{{ $destination->name }}</h3>
                    </div>
                </article>
            @empty
                <article class="card" style="padding: 1.25rem;">
                    <p style="margin: 0; color: #5c6975;">No destinations are available at the moment.</p>
                </article>
            @endforelse
        </div>
    </div>
</section>
