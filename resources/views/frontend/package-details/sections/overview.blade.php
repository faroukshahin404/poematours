<section class="package-section" id="overview">
    <div class="container package-overview">
        <div class="package-overview__content">
            <h2>Overview</h2>
            <p class="package-overview__lead">
                The mysteries of Egypt have captivated travellers for centuries. Now it is your turn to explore the Land of the Pharaohs with an expert A&amp;K Egyptologist at your side as you discover its quintessential archaeological treasures and cruise for four nights along the Nile aboard Sun Boat IV, an A&amp;K Sanctuary.
            </p>
            <p class="package-overview__support">
                Embark on a four-night Nile river cruise, gliding in comfort through stirring desert landscapes and going ashore to discover ancient Egypt's most awe-inspiring sites.
            </p>
            <ul class="package-overview__highlights">
                <li>Be among some of the first visitors to the Grand Egyptian Museum in Giza, viewing its spectacular galleries and the treasures from King Tut's tomb.</li>
                <li>The enigmatic Great Sphinx awaits you on the Giza Plateau, where you step from the light of day into the shadowy confines of a pyramid for an unforgettable visit.</li>
                <li>Descend into the tomb of Tutankhamun (King Tut) and the tomb of Seti I, father of legendary Ramses II, in the Valley of the Kings.</li>
                <li>Nefertari was one of Egypt's most famous queens, whose colorful tomb you enter to admire the still-vivid color of its walls.</li>
                <li>The imposing twin temples of Ramses II and Queen Nefertari in Abu Simbel are wonders to behold, especially when your group's Egyptologist explains their history to you firsthand.</li>
            </ul>
            <a href="#" class="package-download">
                <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4v10m0 0l-4-4m4 4l4-4M5 18h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                <span>Download PDF</span>
            </a>
        </div>
        <div class="package-overview__gallery" data-detail-gallery>
            @foreach($details['overview']['gallery'] as $image)
                <button type="button" class="package-overview__image" data-lightbox-trigger data-src="{{ asset($image) }}">
                    <img src="{{ asset($image) }}" alt="Package overview image {{ $loop->iteration }}">
                </button>
            @endforeach
        </div>
    </div>
</section>
