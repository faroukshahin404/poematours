<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Boat;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\Media;
use App\Models\PackageCategory;
use App\Models\PackageDatePrice;
use App\Models\PackageDatePriceAccommodation;
use App\Models\PackageLabel;
use App\Models\PackageLabelGroup;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TravelPackageSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('is_admin', true)->orderBy('id')->first();
        if (! $admin) {
            return;
        }

        if (PackageCategory::query()->count() === 0) {
            return;
        }

        $labelGroups = PackageLabelGroup::query()->get();
        $labels = PackageLabel::query()->get();
        $activities = Activity::query()->get();
        $destinations = Destination::query()->get();
        $hotels = Hotel::query()->get();
        $boats = Boat::query()->get();
        $rooms = HotelRoom::query()->get();

        $placeholderImages = $this->placeholderImagePaths();

        TravelPackage::factory()
            ->count(6)
            ->create([
                'created_by' => $admin->id,
                'updated_by' => null,
            ])
            ->each(function (TravelPackage $package) use ($admin, $labelGroups, $labels, $activities, $destinations, $hotels, $boats, $rooms, $placeholderImages): void {
                $package->categories()->sync(
                    PackageCategory::query()
                        ->inRandomOrder()
                        ->limit(random_int(1, min(3, max(1, PackageCategory::query()->count()))))
                        ->pluck('id')
                        ->all()
                );

                if ($labelGroups->isNotEmpty()) {
                    $package->labelGroups()->sync(
                        $labelGroups->random(random_int(1, min(2, $labelGroups->count())))->pluck('id')->all()
                    );
                }

                if ($labels->isNotEmpty()) {
                    $package->labels()->sync(
                        $labels->random(random_int(1, min(5, $labels->count())))->pluck('id')->all()
                    );
                }

                if ($activities->isNotEmpty()) {
                    $package->activities()->sync(
                        $activities->random(random_int(1, min(4, $activities->count())))->pluck('id')->all()
                    );
                }

                if ($destinations->isNotEmpty()) {
                    $days = random_int(2, 5);
                    for ($i = 1; $i <= $days; $i++) {
                        $destination = $destinations->random();
                        $hotel = $hotels->isNotEmpty() ? $hotels->random() : null;
                        $boat = $boats->isNotEmpty() ? $boats->random() : null;

                        $package->itineraries()->create([
                            'title' => "Day {$i}",
                            'description' => fake()->sentence(12),
                            'breakfast' => (bool) random_int(0, 1),
                            'lunch' => (bool) random_int(0, 1),
                            'dinner' => (bool) random_int(0, 1),
                            'snacks' => (bool) random_int(0, 1),
                            'destination_id' => $destination->id,
                            'hotel_id' => $hotel?->id,
                            'boat_id' => $boat?->id,
                            'sort_order' => $i,
                        ]);
                    }
                }

                $windows = random_int(1, 3);
                for ($w = 0; $w < $windows; $w++) {
                    $start = now()->addDays(($w * 30) + random_int(5, 12));
                    $end = (clone $start)->addDays(random_int(3, 9));
                    $basePrice = random_int(12000, 45000);
                    $offer = random_int(0, 1) ? random_int(500, 5000) : null;

                    /** @var PackageDatePrice $datePrice */
                    $datePrice = $package->datePrices()->create([
                        'from_date' => $start->toDateString(),
                        'to_date' => $end->toDateString(),
                        'available_seats' => random_int(8, 40),
                        'price' => $basePrice,
                        'offer' => $offer,
                    ]);

                    if ($hotels->isNotEmpty() && $rooms->isNotEmpty()) {
                        $rows = random_int(1, 2);
                        for ($r = 0; $r < $rows; $r++) {
                            $hotel = $hotels->random();
                            $hotelRooms = $rooms->where('hotel_id', $hotel->id);
                            if ($hotelRooms->isEmpty()) {
                                $hotelRooms = $rooms;
                            }
                            if ($hotelRooms->isEmpty()) {
                                continue;
                            }
                            $room = $hotelRooms->random();

                            PackageDatePriceAccommodation::query()->create([
                                'package_date_price_id' => $datePrice->id,
                                'hotel_id' => $hotel->id,
                                'room_id' => $room->id,
                            ]);
                        }
                    }
                }

                if ($placeholderImages !== []) {
                    $galleryCount = random_int(2, min(5, count($placeholderImages)));
                    $gallery = collect($placeholderImages)->random($galleryCount);
                    foreach ((array) $gallery as $path) {
                        Media::query()->create([
                            'path' => $path,
                            'storage_path' => 'placeholders',
                            'model_type' => TravelPackage::class,
                            'model_id' => $package->id,
                            'created_by' => $admin->id,
                        ]);
                    }
                }
            });
    }

    /**
     * @return array<int, string>
     */
    private function placeholderImagePaths(): array
    {
        $directory = public_path('assets/images/placeholders');
        if (! File::isDirectory($directory)) {
            return [];
        }

        return collect(File::files($directory))
            ->filter(function ($file): bool {
                $name = $file->getFilename();
                if (Str::contains($name, ':Zone.Identifier')) {
                    return false;
                }

                $extension = strtolower($file->getExtension());

                return in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'avif', 'gif'], true);
            })
            ->map(fn ($file): string => 'assets/images/placeholders/'.$file->getFilename())
            ->values()
            ->all();
    }
}
