<section class="section home-stories">
    <div class="container">
        <div class="home-section-head">
            <p>{{ $homeStories['eyebrow'] ?? 'Enhance Your Journey' }}</p>
            <h2>{{ $homeStories['title'] ?? 'Add these experiences to your trip' }}</h2>
        </div>
        <div class="home-stories__grid">
            @foreach (($homeStories['items'] ?? []) as $item)
                <a href="{{ url($item['link'] ?? '/') }}" class="home-stories__card" aria-label="{{ $item['title'] ?? 'Story card' }}">
                    <img src="{{ asset($item['image'] ?? 'assets/images/placeholders/template-1.jpeg') }}" alt="{{ $item['title'] ?? 'Story image' }}">
                    <div class="home-stories__content">
                        <h3>{{ $item['title'] ?? '' }}</h3>
                        <p>{{ $item['description'] ?? '' }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
