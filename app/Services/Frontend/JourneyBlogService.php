<?php

namespace App\Services\Frontend;

class JourneyBlogService
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        return [
            [
                'slug' => 'curated-journey-cairo-to-aswan',
                'title' => 'A Curated Journey Through Egypt: From Cairo to Aswan',
                'excerpt' => 'Experience Egypt through a thoughtfully designed route that balances iconic landmarks, private cultural moments, and refined Nile hospitality.',
                'category' => 'Culture',
                'cover_image' => 'assets/images/placeholders/banner.jpeg',
                'gallery' => [
                    'assets/images/placeholders/banner.jpeg',
                    'assets/images/placeholders/pyramids.avif',
                    'assets/images/placeholders/nile-2.jpeg',
                ],
                'content' => [
                    'Egypt rewards travelers who move with intention. Beyond a checklist of famous monuments, the country invites you into layered stories, living neighborhoods, and landscapes that shift from bustling city streets to serene river horizons.',
                    'Start in Cairo with a private guide who understands both history and pace. A thoughtfully sequenced day at the Grand Egyptian Museum and Giza Plateau transforms iconic sites into meaningful context, not rushed photo stops.',
                    'From Cairo, transition south and let the Nile set the rhythm. A four-night cruise creates the right cadence: mornings for exploration, afternoons for stillness on deck, and evenings for unhurried reflection. The route from Luxor to Aswan is not only scenic; it is emotionally cumulative.',
                    'Along the way, prioritize depth over volume. Choose fewer stops, spend longer in each place, and leave room for unplanned moments: local conversations, artisan workshops, and a sunset tea that becomes your favorite memory of the trip.',
                    'The best journeys in Egypt are not merely luxurious, they are personal. When logistics disappear into the background and every detail is curated around your interests, the destination opens itself in a way that feels both intimate and unforgettable.',
                ],
            ],
            [
                'slug' => 'luxury-weekends-in-cairo',
                'title' => 'Luxury Weekends in Cairo',
                'excerpt' => 'A short escape focused on museums, old-city walks, and premium dining in Egypt\'s capital.',
                'category' => 'Culture',
                'cover_image' => 'assets/images/placeholders/pyramids.avif',
                'gallery' => [
                    'assets/images/placeholders/pyramids.avif',
                    'assets/images/placeholders/template-1.jpeg',
                    'assets/images/placeholders/banner.jpeg',
                ],
                'content' => [
                    'Cairo can feel vast, but with the right curation even a weekend becomes deeply rewarding. Focus on a compact radius of experiences and let each one breathe.',
                    'Build your itinerary around two anchors: one heritage-rich morning and one contemporary cultural afternoon. Pair museum visits with high-quality local dining rather than adding unnecessary transfers.',
                    'Luxury in Cairo is about access, timing, and comfort. Early private entries, trusted local hosts, and seamless transport create a weekend that feels polished from arrival to departure.',
                ],
            ],
            [
                'slug' => 'nile-cruise-essentials',
                'title' => 'Nile Cruise Essentials',
                'excerpt' => 'What to pack, when to travel, and how to choose the right four-night sailing experience.',
                'category' => 'Cruise',
                'cover_image' => 'assets/images/placeholders/nile-2.jpeg',
                'gallery' => [
                    'assets/images/placeholders/nile-2.jpeg',
                    'assets/images/placeholders/nile-1.avif',
                    'assets/images/placeholders/sea-5.jpg',
                ],
                'content' => [
                    'A Nile cruise is the most elegant way to connect Egypt\'s southern highlights. The journey is not only about sites; it is about the transition between them.',
                    'Pack for flexibility: breathable layers, comfortable walking shoes, and one refined evening outfit. Choose boats known for service quality and manageable group sizes.',
                    'The best itineraries alternate active mornings with restorative afternoons. That balance keeps energy high and allows you to absorb each destination fully.',
                ],
            ],
            [
                'slug' => 'family-friendly-egypt-itineraries',
                'title' => 'Family-Friendly Egypt Itineraries',
                'excerpt' => 'Balanced journeys with flexible pacing, engaging stops, and child-friendly activities.',
                'category' => 'Family',
                'cover_image' => 'assets/images/placeholders/template-1.jpeg',
                'gallery' => [
                    'assets/images/placeholders/template-1.jpeg',
                    'assets/images/placeholders/sea-1.jpg',
                    'assets/images/placeholders/nile-3.jpg',
                ],
                'content' => [
                    'Traveling Egypt as a family works best when each day includes one headline activity and one lighter shared moment.',
                    'Use shorter transfer windows, interactive guides, and hands-on cultural stops to keep all age groups engaged.',
                    'A family itinerary should feel structured but forgiving, with enough margin for rest, spontaneity, and discovery.',
                ],
            ],
            [
                'slug' => 'red-sea-relaxation-guide',
                'title' => 'Red Sea Relaxation Guide',
                'excerpt' => 'Pair your cultural journey with coastal calm, snorkeling, and premium seaside stays.',
                'category' => 'Leisure',
                'cover_image' => 'assets/images/placeholders/sea-2.jpg',
                'gallery' => [
                    'assets/images/placeholders/sea-2.jpg',
                    'assets/images/placeholders/sea-1.jpg',
                    'assets/images/placeholders/sea-5.jpg',
                ],
                'content' => [
                    'After temple-rich days, the Red Sea offers the ideal contrast: clear water, slower mornings, and understated luxury.',
                    'Choose properties that provide both privacy and activity options so your stay can adapt to your mood each day.',
                    'A short Red Sea extension turns a strong itinerary into a complete one, balancing cultural depth with restorative time.',
                ],
            ],
            [
                'slug' => 'best-time-to-visit-luxor',
                'title' => 'The Best Time to Visit Luxor',
                'excerpt' => 'Season-by-season guidance for temple visits, local experiences, and weather comfort.',
                'category' => 'Culture',
                'cover_image' => 'assets/images/placeholders/nile-1.avif',
                'gallery' => [
                    'assets/images/placeholders/nile-1.avif',
                    'assets/images/placeholders/nile-2.jpeg',
                    'assets/images/placeholders/pyramids.avif',
                ],
                'content' => [
                    'Luxor is rewarding year-round, but your experience changes meaningfully by season.',
                    'Cooler months suit longer archaeological days, while shoulder seasons offer a balance of value and comfort.',
                    'Plan site timing intentionally; early starts and curated routing dramatically improve quality of visit.',
                ],
            ],
            [
                'slug' => 'private-journeys-for-couples',
                'title' => 'Private Journeys for Couples',
                'excerpt' => 'Romantic travel ideas with private guides, elegant stays, and curated experiences.',
                'category' => 'Leisure',
                'cover_image' => 'assets/images/placeholders/sea-5.jpg',
                'gallery' => [
                    'assets/images/placeholders/sea-5.jpg',
                    'assets/images/placeholders/banner.jpeg',
                    'assets/images/placeholders/sea-2.jpg',
                ],
                'content' => [
                    'Egypt is ideal for couples when the journey is tailored around pace, privacy, and meaningful shared moments.',
                    'Blend iconic highlights with intimate experiences: private dinners, small-group excursions, and scenic river time.',
                    'The strongest couple itineraries are not packed; they are curated to create space for connection.',
                ],
            ],
        ];
    }

    public function findBySlug(string $slug): ?array
    {
        foreach ($this->all() as $blog) {
            if (($blog['slug'] ?? null) === $slug) {
                return $blog;
            }
        }

        return null;
    }
}
