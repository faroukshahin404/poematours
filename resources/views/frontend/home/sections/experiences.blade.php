<section class="section" style="background: #fff;">
    <div class="container">
        <p style="margin: 0; color: #9a6c17; letter-spacing: 0.09em; text-transform: uppercase; font-weight: 600; font-size: 0.8rem;">Moments You'll Live</p>
        <h2 class="section-title">Not a checklist. A set of memories you'll carry forever.</h2>
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-top: 1.6rem;">
            @forelse (($journeyHighlights ?? []) as $blog)
                @include('frontend.our-journies.blog-card', ['blog' => $blog])
            @empty
                <p style="margin: 0; color: #5c6975;">No journey stories are available right now.</p>
            @endforelse
        </div>
    </div>
</section>
