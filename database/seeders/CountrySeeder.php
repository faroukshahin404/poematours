<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (Country::query()->count() > 0) {
            return;
        }

        $admin = User::query()->where('is_admin', true)->orderBy('id')->first();
        if ($admin === null) {
            return;
        }

        foreach (['USA', 'Egypt'] as $name) {
            Country::query()->create([
                'name' => $name,
                'slug' => Country::generateUniqueSlug($name),
                'created_by' => $admin->id,
            ]);
        }
    }
}
