<section class="section home-stories">
    <div class="container">
        <div class="home-section-head">
            <p>Enhance Your Journey</p>
            <h2>Add these experiences to your trip</h2>
        </div>
        <div class="home-stories__grid">
            <a href="{{ route('our.journeys.show', 'family-friendly-egypt-itineraries') }}" class="home-stories__card" aria-label="Read details about Farmers Market Visit and Tour">
                <img src="{{ asset('assets/images/placeholders/nile-4.webp') }}" alt="Farmers market dinner experience">
                <div class="home-stories__content">
                    <h3>Farmers Market Visit &amp; Tour</h3>
                    <p>Meet locals, enjoy authentic food moments, and discover hidden countryside charm in one curated experience.</p>
                </div>
            </a>

            <a href="{{ route('our.journeys.show', 'luxury-weekends-in-cairo') }}" class="home-stories__card" aria-label="Read details about Folk Dance Dinner Show">
                <img src="{{ asset('assets/images/placeholders/sea-2.jpg') }}" alt="Folk dance dinner show experience">
                <div class="home-stories__content">
                    <h3>Folk Dance Dinner Show</h3>
                    <p>Enjoy Turkish dances, cave-dinner vibes, and private round-trip transfer with seamless planning.</p>
                </div>
            </a>

            <a href="{{ route('our.journeys.show', 'best-time-to-visit-luxor') }}" class="home-stories__card" aria-label="Read details about Hot Air Balloon Ride">
                <img src="{{ asset('assets/images/placeholders/template-1.jpeg') }}" alt="Hot air balloon ride experience">
                <div class="home-stories__content">
                    <h3>Hot Air Balloon Ride</h3>
                    <p>Float above iconic landscapes at sunrise and capture one of the most memorable views of your journey.</p>
                </div>
            </a>
        </div>
    </div>
</section>
