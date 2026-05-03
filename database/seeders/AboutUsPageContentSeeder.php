<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Seeder;

class AboutUsPageContentSeeder extends Seeder
{
    public function run(): void
    {
        $aboutUsPage = Page::query()->where('slug', 'about-us')->first();
        if (! $aboutUsPage) {
            return;
        }

        $sections = [
            [
                'key' => 'about_hero',
                'order' => 1,
                'is_active' => true,
                'content' => [
                    'breadcrumb_home_label' => 'Home',
                    'breadcrumb_current_label' => 'About Us',
                    'title' => 'Poema Tours Company Profile',
                    'subtitle' => 'Crafting elevated travel experiences across Egypt with heritage, care, and detail.',
                ],
            ],
            [
                'key' => 'about_welcome',
                'order' => 2,
                'is_active' => true,
                'content' => [
                    'title' => 'Welcome to Poema Tours',
                    'paragraphs' => [
                        'Bridging cultures with authentic Egyptian journeys that touch your heart and open your mind. Where American Warmth Meets Egyptian Soul',
                        'At Poema Tours, luxury is not defined by excess, but by intention. We create journeys that feel seamless, unhurried, and deeply personal-where every detail is considered, and every moment has meaning.',
                        'Founded by an American-Egyptian family, Poema exists at the intersection of two worlds: the clarity, professionalism, and ease expected by discerning travelers, and the richness, hospitality, and soul of Egypt as it is truly lived.',
                    ],
                    'image' => 'assets/images/placeholders/team.avif',
                    'image_alt' => 'Poema Tours team',
                ],
            ],
            [
                'key' => 'about_services',
                'order' => 3,
                'is_active' => true,
                'content' => [
                    'title' => 'Our Services',
                    'items' => [
                        ['title' => 'Tailor-Made Journeys', 'description' => 'Personalized itineraries designed around your interests, pace, and travel style.'],
                        ['title' => 'Luxury Accommodation', 'description' => 'Handpicked hotels, boutique resorts, and Nile stays chosen for quality and character.'],
                        ['title' => 'Private Transfers', 'description' => 'Seamless transport and airport assistance for comfort and peace of mind.'],
                        ['title' => 'Expert Local Guides', 'description' => 'Certified Egyptologists and destination specialists enriching every step of the journey.'],
                    ],
                ],
            ],
            [
                'key' => 'about_gallery',
                'order' => 4,
                'is_active' => true,
                'content' => [
                    'title' => 'Gallery',
                    'images' => [
                        ['image' => 'assets/images/placeholders/banner.jpeg', 'alt' => 'Poema Tours gallery image'],
                        ['image' => 'assets/images/placeholders/pyramids.avif', 'alt' => 'Poema Tours gallery image'],
                        ['image' => 'assets/images/placeholders/nile-1.avif', 'alt' => 'Poema Tours gallery image'],
                        ['image' => 'assets/images/placeholders/sea-1.jpg', 'alt' => 'Poema Tours gallery image'],
                        ['image' => 'assets/images/placeholders/template-1.jpeg', 'alt' => 'Poema Tours gallery image'],
                        ['image' => 'assets/images/placeholders/sea-5.jpg', 'alt' => 'Poema Tours gallery image'],
                    ],
                ],
            ],
            [
                'key' => 'about_latest_blogs',
                'order' => 5,
                'is_active' => true,
                'content' => [
                    'title' => 'Latest Blogs',
                    'items' => [
                        [
                            'title' => '5 Reasons Egypt Should Be Your Next Luxury Escape',
                            'description' => 'Discover how timeless heritage, refined accommodation, and curated private experiences make Egypt one of the most compelling destinations for premium travelers.',
                            'image' => 'assets/images/placeholders/template-1.jpeg',
                        ],
                        [
                            'title' => 'How to Plan the Perfect Nile Journey',
                            'description' => 'From selecting the right season to balancing temple visits with river downtime, this guide helps you build a seamless and memorable Nile itinerary.',
                            'image' => 'assets/images/placeholders/nile-3.jpg',
                        ],
                        [
                            'title' => 'Hidden Cultural Gems Beyond the Landmarks',
                            'description' => 'Explore local stories, artisanal encounters, and lesser-known historic corners that elevate your journey far beyond the classic postcard moments.',
                            'image' => 'assets/images/placeholders/sea-1.jpg',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($sections as $section) {
            PageSection::query()->firstOrCreate(
                ['page_id' => $aboutUsPage->id, 'key' => $section['key']],
                [
                    'order' => $section['order'],
                    'is_active' => $section['is_active'],
                    'content' => $section['content'],
                ]
            );
        }
    }
}
