<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Seeder;

class HomePageContentSeeder extends Seeder
{
    public function run(): void
    {
        $homePage = Page::query()->where('slug', 'home')->first();
        if (! $homePage) {
            return;
        }

        $sections = [
            [
                'key' => 'home_hero',
                'order' => 1,
                'is_active' => true,
                'content' => [
                    'eyebrow' => 'Experience Delight.',
                    'title' => 'We\'ll take care of everything.',
                    'cta_text' => 'Design Your Tour',
                    'cta_url' => '/packages',
                    'background_image' => 'assets/images/placeholders/banner.jpeg',
                    'background_image_alt' => 'Golden sunrise over Egypt landscapes',
                    'trust_items' => [
                        ['line_1' => 'American-Egyptian', 'line_2' => 'Owned'],
                        ['line_1' => 'Licensed', 'line_2' => 'Egyptologists'],
                        ['line_1' => 'Transparent', 'line_2' => 'Inclusions'],
                        ['line_1' => '24/7 On-the-', 'line_2' => 'Ground Support'],
                    ],
                ],
            ],
            [
                'key' => 'home_spirit',
                'order' => 2,
                'is_active' => true,
                'content' => [
                    'eyebrow' => 'Our Signature Approach',
                    'title' => 'Find your dream tour to Egypt here',
                    'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.',
                ],
            ],
            [
                'key' => 'home_tours_across_egypt',
                'order' => 3,
                'is_active' => true,
                'content' => [
                    'eyebrow' => 'Destinations & Themes',
                    'title' => 'Tours Across Egypt',
                    'items' => [
                        ['title' => 'Classical Tours', 'image' => 'assets/images/placeholders/banner.jpeg', 'link' => '/packages'],
                        ['title' => 'Istanbul Tours', 'image' => 'assets/images/placeholders/sea-1.jpg', 'link' => '/packages'],
                        ['title' => 'Design Your Own', 'image' => 'assets/images/placeholders/sea-2.jpg', 'link' => '/packages'],
                        ['title' => 'Cappadocia Tours', 'image' => 'assets/images/placeholders/template-2.avif', 'link' => '/packages'],
                        ['title' => 'Mediterranean Coast', 'image' => 'assets/images/placeholders/template-1.jpeg', 'link' => '/packages'],
                        ['title' => 'Small Group Tours', 'image' => 'assets/images/placeholders/banner.jpeg', 'link' => '/packages'],
                    ],
                ],
            ],
            [
                'key' => 'home_last_minute_packages',
                'order' => 4,
                'is_active' => true,
                'content' => [
                    'eyebrow' => 'Curated Now',
                    'title' => 'Last Minute Packages',
                    'empty_state' => 'No packages are available at the moment.',
                ],
            ],
            [
                'key' => 'home_stories',
                'order' => 5,
                'is_active' => true,
                'content' => [
                    'eyebrow' => 'Enhance Your Journey',
                    'title' => 'Add these experiences to your trip',
                    'items' => [
                        [
                            'title' => 'Farmers Market Visit & Tour',
                            'description' => 'Meet locals, enjoy authentic food moments, and discover hidden countryside charm in one curated experience.',
                            'image' => 'assets/images/placeholders/nile-4.webp',
                            'link' => '/our-journeys/family-friendly-egypt-itineraries',
                        ],
                        [
                            'title' => 'Folk Dance Dinner Show',
                            'description' => 'Enjoy Turkish dances, cave-dinner vibes, and private round-trip transfer with seamless planning.',
                            'image' => 'assets/images/placeholders/sea-2.jpg',
                            'link' => '/our-journeys/luxury-weekends-in-cairo',
                        ],
                        [
                            'title' => 'Hot Air Balloon Ride',
                            'description' => 'Float above iconic landscapes at sunrise and capture one of the most memorable views of your journey.',
                            'image' => 'assets/images/placeholders/template-1.jpeg',
                            'link' => '/our-journeys/best-time-to-visit-luxor',
                        ],
                    ],
                ],
            ],
            [
                'key' => 'home_why_poema',
                'order' => 6,
                'is_active' => true,
                'content' => [
                    'eyebrow' => 'Our promise',
                    'title' => 'Why Poema',
                    'description' => 'The four things we get right, every single time.',
                    'items' => [
                        ['title' => 'Safety', 'description' => 'Placeholder copy: vetted partners, clear protocols, and thoughtful pacing so you can explore with confidence.'],
                        ['title' => 'Transparency', 'description' => 'Placeholder copy: upfront inclusions, honest timelines, and no surprise fees-what you see is what you receive.'],
                        ['title' => 'Local access', 'description' => 'Placeholder copy: insider routes, respected local hosts, and experiences beyond the typical circuit.'],
                        ['title' => 'Personal care', 'description' => 'Placeholder copy: a dedicated team, responsive support, and itineraries shaped around how you like to travel.'],
                    ],
                ],
            ],
        ];

        foreach ($sections as $section) {
            PageSection::query()->firstOrCreate(
                [
                    'page_id' => $homePage->id,
                    'key' => $section['key'],
                ],
                [
                    'order' => $section['order'],
                    'is_active' => $section['is_active'],
                    'content' => $section['content'],
                ]
            );
        }
    }
}
