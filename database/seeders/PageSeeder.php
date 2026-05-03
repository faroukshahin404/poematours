<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'name' => 'Home',
                'slug' => 'home',
                'meta_title' => 'Poema Tours | Enter Egypt',
                'meta_description' => 'Discover curated Egypt travel experiences with Poema Tours.',
                'meta_keywords' => ['egypt tours', 'luxury egypt travel', 'custom egypt trips'],
                'og_tags' => [
                    'title' => 'Poema Tours | Enter Egypt',
                    'description' => 'Discover curated Egypt travel experiences with Poema Tours.',
                    'type' => 'website',
                ],
            ],
            [
                'name' => 'About Us',
                'slug' => 'about-us',
                'meta_title' => 'About Us | Poema Tours',
                'meta_description' => 'Learn more about Poema Tours and our approach to memorable journeys.',
                'meta_keywords' => ['about poema tours', 'travel company', 'egypt specialists'],
                'og_tags' => [
                    'title' => 'About Us | Poema Tours',
                    'description' => 'Learn more about Poema Tours and our approach to memorable journeys.',
                    'type' => 'website',
                ],
            ],
            [
                'name' => 'Activities',
                'slug' => 'activities',
                'meta_title' => 'Activities | Poema Tours',
                'meta_description' => 'Explore activities and experiences you can add to your Egypt trip.',
                'meta_keywords' => ['egypt activities', 'tour experiences', 'things to do in egypt'],
                'og_tags' => [
                    'title' => 'Activities | Poema Tours',
                    'description' => 'Explore activities and experiences you can add to your Egypt trip.',
                    'type' => 'website',
                ],
            ],
            [
                'name' => 'Terms and Conditions',
                'slug' => 'terms-and-conditions',
                'meta_title' => 'Terms and Conditions | Poema Tours',
                'meta_description' => 'Read Poema Tours terms and conditions for bookings and services.',
                'meta_keywords' => ['terms and conditions', 'booking terms', 'poema tours policy'],
                'og_tags' => [
                    'title' => 'Terms and Conditions | Poema Tours',
                    'description' => 'Read Poema Tours terms and conditions for bookings and services.',
                    'type' => 'website',
                ],
            ],
            [
                'name' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'meta_title' => 'Privacy Policy | Poema Tours',
                'meta_description' => 'Understand how Poema Tours collects, uses, and protects your data.',
                'meta_keywords' => ['privacy policy', 'data protection', 'user privacy'],
                'og_tags' => [
                    'title' => 'Privacy Policy | Poema Tours',
                    'description' => 'Understand how Poema Tours collects, uses, and protects your data.',
                    'type' => 'website',
                ],
            ],
        ];

        foreach ($pages as $pageData) {
            Page::query()->firstOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }
}
