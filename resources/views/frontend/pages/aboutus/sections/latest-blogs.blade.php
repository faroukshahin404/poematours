<section class="about-blogs" style="margin-bottom: 10px;">
    <div class="container">
        <h2 class="section-title">{{ $aboutLatestBlogs['title'] ?? 'Latest Blogs' }}</h2>
        <div class="about-blogs__list">
            @foreach (($aboutLatestBlogs['items'] ?? []) as $blog)
                <article class="about-blog-card {{ $loop->even ? 'about-blog-card--reverse' : '' }}">
                    <div class="about-blog-card__image">
                        <img src="{{ asset($blog['image'] ?? '') }}" alt="{{ $blog['title'] ?? '' }}">
                    </div>
                    <div class="about-blog-card__content">
                        <h3>{{ $blog['title'] ?? '' }}</h3>
                        <p>{{ $blog['description'] ?? '' }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>