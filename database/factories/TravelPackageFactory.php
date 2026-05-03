<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\TravelPackage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TravelPackage>
 */
class TravelPackageFactory extends Factory
{
    protected $model = TravelPackage::class;

    public function definition(): array
    {
        $defaultSlug = Language::defaultSlug();
        $title = $this->faker->unique()->sentence(4);
        $description = $this->faker->paragraph(3);

        return [
            'title' => [
                $defaultSlug => $title,
            ],
            'description' => [
                $defaultSlug => $description,
            ],
            'slug' => Str::slug($title),
            'pdf' => null,
        ];
    }
}
