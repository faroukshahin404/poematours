<section class="section" style="background: #fff;">
    <div class="container">
        <p class="home-reveal-item" style="margin: 0; color: #9a6c17; letter-spacing: 0.09em; text-transform: uppercase; font-weight: 600; font-size: 0.8rem; --home-reveal-item-delay: 0ms;">Moments You'll Live</p>
        <h2 class="section-title home-reveal-item" style="--home-reveal-item-delay: 80ms;">Not a checklist. A set of memories you'll carry forever.</h2>
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-top: 1.6rem;">
            @forelse (($journeyHighlights ?? []) as $blog)
                <div class="home-reveal-item" style="--home-reveal-item-delay: {{ 120 + ($loop->index * 90) }}ms;">
                    @include('frontend.our-journies.blog-card', ['blog' => $blog])
                </div>
            @empty
                <p style="margin: 0; color: #5c6975;">No journey stories are available right now.</p>
            @endforelse
        </div>
    </div>
</section>
