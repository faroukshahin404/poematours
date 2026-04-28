<div class="packages-list">
    @forelse($packages as $package)
        @include('frontend.packages.cards.list-card', ['package' => $package])
    @empty
        <p class="packages-empty">No packages found for the selected criteria.</p>
    @endforelse
</div>
