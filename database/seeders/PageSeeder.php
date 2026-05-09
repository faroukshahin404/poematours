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
            [
                'name' => 'Destinations',
                'slug' => 'destinations',
                'meta_title' => 'Destinations in Egypt | Poema Tours',
                'meta_description' => 'Explore Egypt destinations and curated routes with Poema Tours.',
                'meta_keywords' => ['egypt destinations', 'cairo luxor aswan', 'egypt travel regions'],
                'og_tags' => [
                    'title' => 'Destinations in Egypt | Poema Tours',
                    'description' => 'Explore Egypt destinations and curated routes with Poema Tours.',
                    'type' => 'website',
                ],
            ],
            [
                'name' => 'Our Journeys',
                'slug' => 'our-journeys',
                'meta_title' => 'Our Journeys | Poema Tours',
                'meta_description' => 'Stories, itineraries, and inspiration for traveling Egypt with Poema Tours.',
                'meta_keywords' => ['egypt journeys', 'travel stories', 'poema tours blog'],
                'og_tags' => [
                    'title' => 'Our Journeys | Poema Tours',
                    'description' => 'Stories, itineraries, and inspiration for traveling Egypt with Poema Tours.',
                    'type' => 'website',
                ],
            ],
            [
                'name' => 'Packages',
                'slug' => 'packages',
                'meta_title' => 'Egypt Tour Packages | Poema Tours',
                'meta_description' => 'Browse Egypt tour packages, cruises, and bespoke itineraries.',
                'meta_keywords' => ['egypt packages', 'luxury egypt tours', 'nile cruises'],
                'og_tags' => [
                    'title' => 'Egypt Tour Packages | Poema Tours',
                    'description' => 'Browse Egypt tour packages, cruises, and bespoke itineraries.',
                    'type' => 'website',
                ],
            ],
            [
                'name' => 'Search Packages',
                'slug' => 'search',
                'meta_title' => 'Search Packages | Poema Tours',
                'meta_description' => 'Search Egypt tours by destination, dates, and travel style.',
                'meta_keywords' => ['search egypt tours', 'find egypt packages'],
                'og_tags' => [
                    'title' => 'Search Packages | Poema Tours',
                    'description' => 'Search Egypt tours by destination, dates, and travel style.',
                    'type' => 'website',
                ],
            ],
            [
                'name' => 'Customize Tour',
                'slug' => 'customize',
                'meta_title' => 'Customize Your Egypt Tour | Poema Tours',
                'meta_description' => 'Tell us how you want to experience Egypt and our team will tailor an itinerary.',
                'meta_keywords' => ['custom egypt tour', 'tailor made egypt', 'bespoke travel'],
                'og_tags' => [
                    'title' => 'Customize Your Egypt Tour | Poema Tours',
                    'description' => 'Tell us how you want to experience Egypt and our team will tailor an itinerary.',
                    'type' => 'website',
                ],
            ],
            [
                'name' => 'Reservation',
                'slug' => 'reservation',
                'meta_title' => 'Reservation | Poema Tours',
                'meta_description' => 'Complete your reservation details with Poema Tours.',
                'meta_keywords' => ['book egypt tour', 'poema tours reservation'],
                'og_tags' => [
                    'title' => 'Reservation | Poema Tours',
                    'description' => 'Complete your reservation details with Poema Tours.',
                    'type' => 'website',
                ],
            ],

            [
                'name' => 'Payment Success',
                'slug' => 'payment-success',
                'meta_title' => 'Payment Successful | Poema Tours',
                'meta_description' => 'Your payment was completed successfully.',
                'meta_keywords' => ['payment success', 'booking confirmation'],
                'og_tags' => [
                    'title' => 'Payment Successful | Poema Tours',
                    'description' => 'Your payment was completed successfully.',
                    'type' => 'website',
                ],
            ],
            [
                'name' => 'Payment Failed',
                'slug' => 'payment-failure',
                'meta_title' => 'Payment Unsuccessful | Poema Tours',
                'meta_description' => 'We could not complete your payment. Please try again or contact support.',
                'meta_keywords' => ['payment failed', 'booking payment'],
                'og_tags' => [
                    'title' => 'Payment Unsuccessful | Poema Tours',
                    'description' => 'We could not complete your payment. Please try again or contact support.',
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
