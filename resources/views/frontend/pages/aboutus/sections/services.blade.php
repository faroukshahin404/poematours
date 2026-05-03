<section class="about-services" style="margin-bottom: 10px;">
    <div class="container">
        <h2 class="section-title">{{ $aboutServices['title'] ?? 'Our Services' }}</h2>
        <div class="about-services__grid">
            @foreach (($aboutServices['items'] ?? []) as $item)
                <article class="about-service-card">
                    <svg class="icon icon--md" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M12 3c4.4 0 8 3.4 8 7.6 0 5.5-6.8 9.8-7.1 10-.6.4-1.2.4-1.8 0-.3-.2-7.1-4.5-7.1-10C4 6.4 7.6 3 12 3z"
                            fill="currentColor"
                        />
                    </svg>
                    <h3>{{ $item['title'] ?? '' }}</h3>
                    <p>{{ $item['description'] ?? '' }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>