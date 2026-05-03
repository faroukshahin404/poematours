<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Seeder;

class ActivitiesPageContentSeeder extends Seeder
{
    public function run(): void
    {
        $activitiesPage = Page::query()->where('slug', 'activities')->first();
        if (! $activitiesPage) {
            return;
        }

        PageSection::query()->firstOrCreate(
            [
                'page_id' => $activitiesPage->id,
                'key' => 'activities_labels',
            ],
            [
                'order' => 1,
                'is_active' => true,
                'content' => [
                    'hero_title_suffix' => 'Activities',
                    'hero_view_journeys_label' => 'View All Journeys',
                    'breadcrumb_home_label' => 'Home',
                    'breadcrumb_packages_label' => 'Packages',
                    'hero_side_title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt.',
                    'hero_side_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.',
                    'intro_title_template' => ':activity in Egypt',
                    'trips_title_template' => ':activity Trips',
                    'empty_trips_label' => 'No trips are available for this activity yet.',
                    'activities_list_title' => 'All Activities',
                ],
            ]
        );
    }
}
