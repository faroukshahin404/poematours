<section class="about-blogs" style="margin-bottom: 10px;">
    <div class="container">
        <h2 class="section-title">Latest Blogs</h2>
        <div class="about-blogs__list">
            @foreach ([
                ['title' => '5 Reasons Egypt Should Be Your Next Luxury Escape', 'description' => 'Discover how timeless heritage, refined accommodation, and curated private experiences make Egypt one of the most compelling destinations for premium travelers.', 'image' => 'assets/images/placeholders/template-1.jpeg'],
                ['title' => 'How to Plan the Perfect Nile Journey', 'description' => 'From selecting the right season to balancing temple visits with river downtime, this guide helps you build a seamless and memorable Nile itinerary.', 'image' => 'assets/images/placeholders/nile-3.jpg'],
                ['title' => 'Hidden Cultural Gems Beyond the Landmarks', 'description' => 'Explore local stories, artisanal encounters, and lesser-known historic corners that elevate your journey far beyond the classic postcard moments.', 'image' => 'assets/images/placeholders/sea-1.jpg'],
            ] as $blog)
                <article class="about-blog-card {{ $loop->even ? 'about-blog-card--reverse' : '' }}">
                    <div class="about-blog-card__image">
                        <img src="{{ asset($blog['image']) }}" alt="{{ $blog['title'] }}">
                    </div>
                    <div class="about-blog-card__content">
                        <h3>{{ $blog['title'] }}</h3>
                        <p>{{ $blog['description'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>