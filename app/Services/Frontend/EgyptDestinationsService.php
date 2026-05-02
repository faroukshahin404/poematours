<?php

namespace App\Services\Frontend;

class EgyptDestinationsService
{
    /**
     * Gallery tiles for the public destinations page (Egypt).
     *
     * `packages_query` is passed to packages.index when non-empty (must match seeded package destinations).
     *
     * @return list<array{name: string, image: string, size: 'short'|'medium'|'tall'|'xlarge', packages_query: array<string, string>}>
     */
    public function galleryItems(): array
    {
        return [
            [
                'name' => 'Cairo',
                'image' => 'assets/images/placeholders/banner.jpeg',
                'size' => 'medium',
                'packages_query' => ['destination' => 'Cairo'],
            ],
            [
                'name' => 'Luxor',
                'image' => 'assets/images/placeholders/nile-2.jpeg',
                'size' => 'tall',
                'packages_query' => ['destination' => 'Luxor'],
            ],
            [
                'name' => 'Aswan',
                'image' => 'assets/images/placeholders/nile-3.jpg',
                'size' => 'short',
                'packages_query' => ['destination' => 'Aswan'],
            ],
            [
                'name' => 'Alexandria',
                'image' => 'assets/images/placeholders/sea-1.jpg',
                'size' => 'xlarge',
                'packages_query' => [],
            ],
            [
                'name' => 'Giza',
                'image' => 'assets/images/placeholders/pyramids.avif',
                'size' => 'tall',
                'packages_query' => ['destination' => 'Cairo'],
            ],
            [
                'name' => 'Sharm El Sheikh',
                'image' => 'assets/images/placeholders/sea-4.webp',
                'size' => 'short',
                'packages_query' => [],
            ],
            [
                'name' => 'Hurghada',
                'image' => 'assets/images/placeholders/sea-2.jpg',
                'size' => 'medium',
                'packages_query' => [],
            ],
            [
                'name' => 'Siwa Oasis',
                'image' => 'assets/images/placeholders/template-2.avif',
                'size' => 'xlarge',
                'packages_query' => [],
            ],
            [
                'name' => 'Dahab',
                'image' => 'assets/images/placeholders/sea-3.avif',
                'size' => 'medium',
                'packages_query' => [],
            ],
            [
                'name' => 'Abu Simbel',
                'image' => 'assets/images/placeholders/nile-4.webp',
                'size' => 'short',
                'packages_query' => ['destination' => 'Aswan'],
            ],
            [
                'name' => 'Nile Valley',
                'image' => 'assets/images/placeholders/nile-1.avif',
                'size' => 'tall',
                'packages_query' => ['destination' => 'Luxor'],
            ],
            [
                'name' => 'Marsa Alam',
                'image' => 'assets/images/placeholders/sea-5.jpg',
                'size' => 'medium',
                'packages_query' => [],
            ],
        ];
    }
}
