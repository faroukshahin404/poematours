<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Language;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $checkAdmins = User::count();
        if ($checkAdmins === 0) {
            User::factory()->admin()->create([
                'name' => 'Admin User',
                'email' => 'admin@poematours.com',
                'password' => Hash::make('123'),
            ]);
        }

        $admin = User::query()->where('is_admin', true)->orderBy('id')->first();
        if ($admin !== null && Language::query()->count() === 0) {
            Language::query()->create([
                'name' => 'English',
                'slug' => 'english',
                'is_default' => true,
                'created_by' => $admin->id,
                'updated_by' => null,
            ]);
        }

        if ($admin !== null && Currency::query()->count() === 0) {
            Currency::query()->create([
                'name' => 'Egyptian Pound',
                'slug' => 'egyptian-pound',
                'is_default' => true,
                'created_by' => $admin->id,
                'updated_by' => null,
            ]);
        }

        if ($admin !== null && Country::query()->count() === 0) {
            $this->call(CountrySeeder::class);
        }

        $this->call([
            PageSeeder::class,
            HomePageContentSeeder::class,
            ActivitiesPageContentSeeder::class,
            AboutUsPageContentSeeder::class,
            LegalPagesContentSeeder::class,
        ]);

        if (TravelPackage::query()->count() === 0) {
            $this->call(TravelPackageSeeder::class);
        }
    }
}
