<div class="packages-grid">
    @forelse($packages as $package)
        @include('frontend.packages.cards.grid-card', ['package' => $package])
    @empty
        <p class="packages-empty">No packages found for the selected criteria.</p>
    @endforelse
</div>
