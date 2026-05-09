{{-- Shared shell for Terms of Use and Privacy Policy (CMS section body or Page.body HTML). --}}
<section class="legal-page">
    <div class="container legal-page__container">
        <nav class="packages-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">{{ $legal['breadcrumb_home_label'] ?? 'Home' }}</a>
            <span>/</span>
            <span>{{ $legal['breadcrumb_current_label'] ?? '' }}</span>
        </nav>
        <h1>{{ $legal['title'] ?? '' }}</h1>
        <div class="legal-page__body">{!! $legal['body'] ?? '' !!}</div>
    </div>
</section>
