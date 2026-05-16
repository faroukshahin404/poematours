<section class="section home-cta" aria-labelledby="home-cta-heading">
    <div class="container">
        <div class="home-cta__panel">
            <p class="home-cta__eyebrow">{{ __('A Journey Made For You') }}</p>
            <h2 id="home-cta-heading" class="home-cta__title">{{ __('Tell us your dream, and we will shape it with care.') }}</h2>
            <p class="home-cta__lede">
                {{ __('Whether you travel for wonder, connection, or peace, we design an experience that feels deeply personal.') }}
            </p>
            <div class="home-cta__actions">
                <a href="{{ route('packages.index') }}" class="home-cta__btn home-cta__btn--primary">{{ __('Plan My Journey') }}</a>
                <a href="{{ route('about.us') }}" class="home-cta__btn home-cta__btn--ghost">{{ __('Talk To A Travel Curator') }}</a>
            </div>
        </div>
        </div>
</section>
