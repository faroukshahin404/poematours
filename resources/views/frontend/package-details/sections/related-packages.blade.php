<section class="package-section" id="related-packages">
    <div class="container related-packages">
        <div class="related-packages__head">
            <h2>Related Packages</h2>
        </div>

        <div class="related-packages__scroll">
            @foreach($relatedPackages as $package)
                <div class="related-packages__item">
                    @include('frontend.packages.cards.grid-card', ['package' => $package])
                </div>
            @endforeach
        </div>
    </div>
</section>
