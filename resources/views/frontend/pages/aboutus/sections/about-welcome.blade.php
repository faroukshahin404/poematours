<section class="about-welcome" style="margin-bottom: 10px;">
    <div class="container about-welcome__grid">
        <div>
            <h2 class="section-title">{{ $aboutWelcome['title'] ?? 'Welcome to Poema Tours' }}</h2>
            @foreach (($aboutWelcome['paragraphs'] ?? []) as $paragraph)
                <p class="about-welcome__lead">{{ $paragraph }}</p>
            @endforeach
        </div>
        <div class="about-welcome__image">
            <img
                src="{{ asset($aboutWelcome['image'] ?? 'assets/images/placeholders/team.avif') }}"
                alt="{{ $aboutWelcome['image_alt'] ?? 'Poema Tours team' }}"
            >
        </div>
    </div>
</section>