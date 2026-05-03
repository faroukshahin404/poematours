<section class="section home-why-poema" aria-labelledby="home-why-poema-heading">
    <div class="container">
        <header class="home-why-poema__intro">
            <p class="home-why-poema__eyebrow">{{ $homeWhyPoema['eyebrow'] ?? 'Our promise' }}</p>
            <h2 id="home-why-poema-heading">{{ $homeWhyPoema['title'] ?? 'Why Poema' }}</h2>
            <p class="home-why-poema__lede">{{ $homeWhyPoema['description'] ?? 'The four things we get right, every single time.' }}</p>
        </header>

        <ul class="home-why-poema__grid" role="list">
            @foreach (($homeWhyPoema['items'] ?? []) as $item)
                <li class="home-why-poema__item">
                    <span class="home-why-poema__icon-wrap" aria-hidden="true">
                        <svg class="home-why-poema__icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 3.25 5.5 6.4v5.1c0 3.55 2.2 6.85 6.5 8.35 4.3-1.5 6.5-4.8 6.5-8.35V6.4L12 3.25Z" stroke="currentColor" stroke-width="1.35" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <h3>{{ $item['title'] ?? '' }}</h3>
                    <p>{{ $item['description'] ?? '' }}</p>
                </li>
            @endforeach
        </ul>
    </div>
</section>
