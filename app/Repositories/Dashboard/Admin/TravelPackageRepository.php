<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\TravelPackageRepositoryInterface;
use App\Enums\StoragePath;
use App\Models\TravelPackage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TravelPackageRepository implements TravelPackageRepositoryInterface
{

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return TravelPackage::query()
            ->with(['categories:id,name,slug', 'labels:id,slug', 'creator:id,name', 'updater:id,name'])
            ->withCount('itineraries')
            ->orderBy('slug')
            ->paginate($perPage);
    }

    public function create(array $data, ?UploadedFile $pdf, array $images, int $userId): TravelPackage
    {
        return DB::transaction(function () use ($data, $pdf, $images, $userId) {
            $slug = TravelPackage::generateUniqueSlug($data['slug']);

            $travelPackage = new TravelPackage;
            $travelPackage->setAttribute('title', $data['title']);
            $travelPackage->setAttribute('description', $data['description']);
            $travelPackage->setAttribute('details', $data['details'] ?? []);
            $travelPackage->setAttribute('slug', $slug);
            $travelPackage->setAttribute('pdf', $pdf ? TravelPackage::storePdf($pdf) : null);
            $travelPackage->setAttribute('created_by', $userId);
            $travelPackage->setAttribute('featured', (int) ($data['featured'] ?? 0));
            $travelPackage->setAttribute('recommended', (int) ($data['recommended'] ?? 0));
            $travelPackage->setAttribute('is_private', (int) ($data['is_private'] ?? 0));
            $travelPackage->setAttribute('is_small_group', (int) ($data['is_small_group'] ?? 0));
            $travelPackage->save();

            $this->syncRelations($travelPackage, $data);
            $this->syncExtensions($travelPackage, $data['extensions'] ?? []);
            $this->syncItineraries($travelPackage, $data['itineraries']);
            $this->syncDatePrices($travelPackage, $data['date_prices']);
            $this->uploadGalleryImages($travelPackage, $images, $userId);

            return $travelPackage;
        });
    }

    public function update(TravelPackage $travelPackage, array $data, ?UploadedFile $pdf, array $images, int $userId): TravelPackage
    {
        return DB::transaction(function () use ($travelPackage, $data, $pdf, $images, $userId) {
            $slug = TravelPackage::generateUniqueSlug($data['slug'], $travelPackage->id);

            if ($data['remove_pdf']) {
                TravelPackage::deleteStoredPdf($travelPackage->getRawOriginal('pdf'));
                $travelPackage->pdf = null;
            }
            if ($pdf) {
                TravelPackage::deleteStoredPdf($travelPackage->getRawOriginal('pdf'));
                $travelPackage->pdf = TravelPackage::storePdf($pdf);
            }

            $this->deleteGalleryImagesByIds($travelPackage, $data['remove_media_ids']);
            $this->uploadGalleryImages($travelPackage, $images, $userId);

            $travelPackage->setAttribute('title', $data['title']);
            $travelPackage->setAttribute('description', $data['description']);
            $travelPackage->setAttribute('details', $data['details'] ?? []);
            $travelPackage->setAttribute('slug', $slug);
            $travelPackage->setAttribute('updated_by', $userId);
            $travelPackage->setAttribute('featured', (int) ($data['featured'] ?? 0));
            $travelPackage->setAttribute('recommended', (int) ($data['recommended'] ?? 0));
            $travelPackage->setAttribute('is_private', (int) ($data['is_private'] ?? 0));
            $travelPackage->setAttribute('is_small_group', (int) ($data['is_small_group'] ?? 0));

            $travelPackage->save();

            $this->syncRelations($travelPackage, $data);
            $this->syncExtensions($travelPackage, $data['extensions'] ?? []);
            $travelPackage->itineraries()->delete();
            $travelPackage->datePrices()->delete();
            $this->syncItineraries($travelPackage, $data['itineraries']);
            $this->syncDatePrices($travelPackage, $data['date_prices']);

            return $travelPackage;
        });
    }

    public function delete(TravelPackage $travelPackage, int $userId): void
    {
        DB::transaction(function () use ($travelPackage): void {
            $this->deleteAllGalleryImages($travelPackage);
            TravelPackage::deleteStoredPdf($travelPackage->getRawOriginal('pdf'));
            $travelPackage->delete();
        });
    }

    public function duplicate(TravelPackage $travelPackage, int $userId): TravelPackage
    {
        return DB::transaction(function () use ($travelPackage, $userId): TravelPackage {
            $travelPackage->loadMissing([
                'categories:id',
                'labelGroups:id',
                'labels:id',
                'activities:id',
                'inclusions:id',
                'extensions:id',
                'itineraries:id,package_id,title,description,breakfast,lunch,dinner,snacks,destination_id,hotel_id,boat_id,sort_order',
                'datePrices.accommodations:id,package_date_price_id,hotel_id,room_id',
                'media:id,path,storage_path',
            ]);

            $copy = new TravelPackage;
            $copy->slug = TravelPackage::generateUniqueSlug($travelPackage->slug.'-copy');
            $copy->setAttribute('title', $this->appendCopyToDefaultTitle($travelPackage->titleTranslations()));
            $copy->setAttribute('description', $travelPackage->descriptionTranslations());
            $copy->setAttribute('details', $travelPackage->detailsData());
            $copy->pdf = $this->duplicatePublicFilePath($travelPackage->getRawOriginal('pdf'));
            $copy->setAttribute('featured', (int) ($travelPackage->featured ?? 0));
            $copy->setAttribute('recommended', (int) ($travelPackage->recommended ?? 0));
            $copy->setAttribute('is_private', (int) ($travelPackage->is_private ?? 0));
            $copy->setAttribute('is_small_group', (int) ($travelPackage->is_small_group ?? 0));
            $copy->created_by = $userId;
            $copy->save();

            $copy->categories()->sync($travelPackage->categories->pluck('id')->all());
            $copy->labelGroups()->sync($travelPackage->labelGroups->pluck('id')->all());
            $copy->labels()->sync($travelPackage->labels->pluck('id')->all());
            $copy->activities()->sync($travelPackage->activities->pluck('id')->all());
            $copy->inclusions()->sync($travelPackage->inclusions->pluck('id')->all());

            $extensionSync = [];
            foreach ($travelPackage->extensions as $extension) {
                $extensionSync[$extension->id] = [
                    'type' => $extension->pivot->type,
                    'sort_order' => $extension->pivot->sort_order,
                    'inclusions_text' => $extension->pivot->inclusions_text,
                ];
            }
            $copy->extensions()->sync($extensionSync);

            foreach ($travelPackage->itineraries->sortBy('sort_order')->values() as $itinerary) {
                $copy->itineraries()->create([
                    'title' => $itinerary->title,
                    'description' => $itinerary->description,
                    'breakfast' => (bool) $itinerary->breakfast,
                    'lunch' => (bool) $itinerary->lunch,
                    'dinner' => (bool) $itinerary->dinner,
                    'snacks' => (bool) $itinerary->snacks,
                    'destination_id' => $itinerary->destination_id,
                    'hotel_id' => $itinerary->hotel_id,
                    'boat_id' => $itinerary->boat_id,
                    'sort_order' => $itinerary->sort_order,
                ]);
            }

            foreach ($travelPackage->datePrices as $datePrice) {
                $newDatePrice = $copy->datePrices()->create([
                    'from_date' => $datePrice->from_date,
                    'to_date' => $datePrice->to_date,
                    'available_seats' => $datePrice->available_seats,
                    'price' => $datePrice->price,
                    'offer' => $datePrice->offer,
                ]);

                foreach ($datePrice->accommodations as $accommodation) {
                    $newDatePrice->accommodations()->create([
                        'hotel_id' => $accommodation->hotel_id,
                        'room_id' => $accommodation->room_id,
                    ]);
                }
            }

            foreach ($travelPackage->media as $media) {
                $newPath = $this->duplicatePublicFilePath($media->path);
                if ($newPath === null || $newPath === '') {
                    continue;
                }

                $copy->media()->create([
                    'path' => $newPath,
                    'storage_path' => $media->storage_path,
                    'created_by' => $userId,
                ]);
            }

            return $copy;
        });
    }

    /**
     * @param  array<string, string>  $titles
     * @return array<string, string>
     */
    private function appendCopyToDefaultTitle(array $titles): array
    {
        if ($titles === []) {
            return ['en' => 'Copy'];
        }

        $firstKey = array_key_first($titles);
        if ($firstKey === null) {
            return ['en' => 'Copy'];
        }

        $titles[$firstKey] = trim($titles[$firstKey].' (Copy)');

        return $titles;
    }

    private function duplicatePublicFilePath(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        $sourcePath = public_path('storage/'.$path);
        if (! is_file($sourcePath)) {
            return $path;
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $directory = trim(pathinfo($path, PATHINFO_DIRNAME), '.');
        $copyName = $filename.'-copy-'.Str::random(6).($extension !== '' ? '.'.$extension : '');
        $targetRelative = ($directory !== '' ? $directory.'/' : '').$copyName;
        $targetPath = public_path('storage/'.$targetRelative);

        $targetDir = dirname($targetPath);
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        copy($sourcePath, $targetPath);

        return $targetRelative;
    }

    /**
     * @param  array<int, UploadedFile>  $images
     */
    private function uploadGalleryImages(TravelPackage $travelPackage, array $images, int $userId): void
    {
        foreach ($images as $file) {
            $path = TravelPackage::storeMediaFile($file);
            $travelPackage->media()->create([
                'path' => $path,
                'storage_path' => StoragePath::Packages->value,
                'created_by' => $userId,
            ]);
        }
    }

    /**
     * @param  array<int, int>  $mediaIds
     */
    private function deleteGalleryImagesByIds(TravelPackage $travelPackage, array $mediaIds): void
    {
        if ($mediaIds === []) {
            return;
        }

        $mediaItems = $travelPackage->media()->whereIn('id', $mediaIds)->get(['id', 'path']);
        foreach ($mediaItems as $media) {
            TravelPackage::deleteStoredFile($media->path);
            $media->delete();
        }
    }

    private function deleteAllGalleryImages(TravelPackage $travelPackage): void
    {
        $mediaItems = $travelPackage->media()->get(['id', 'path']);
        foreach ($mediaItems as $media) {
            TravelPackage::deleteStoredFile($media->path);
            $media->delete();
        }
    }

    private function syncRelations(TravelPackage $travelPackage, array $data): void
    {
        $travelPackage->categories()->sync($data['package_category_ids']);
        $travelPackage->labelGroups()->sync($data['package_label_group_ids']);
        $travelPackage->labels()->sync($data['package_label_ids']);
        $travelPackage->activities()->sync($data['activity_ids']);
        $travelPackage->inclusions()->sync($data['package_inclusion_ids']);
    }

    /**
     * @param  array<int, array{extension_package_id: int, type: string, sort_order: int, inclusions_text: string|null}>  $extensions
     */
    private function syncExtensions(TravelPackage $travelPackage, array $extensions): void
    {
        $sync = [];
        foreach ($extensions as $index => $extension) {
            $extensionPackageId = (int) ($extension['extension_package_id'] ?? 0);
            if ($extensionPackageId === 0 || $extensionPackageId === $travelPackage->id) {
                continue;
            }

            $sync[$extensionPackageId] = [
                'type' => in_array($extension['type'] ?? '', ['pre_tour', 'post_tour'], true)
                    ? $extension['type']
                    : 'pre_tour',
                'sort_order' => (int) ($extension['sort_order'] ?? $index),
                'inclusions_text' => isset($extension['inclusions_text']) && $extension['inclusions_text'] !== ''
                    ? trim((string) $extension['inclusions_text'])
                    : null,
            ];
        }

        $travelPackage->extensions()->sync($sync);
    }

    private function syncItineraries(TravelPackage $travelPackage, array $itineraries): void
    {
        foreach ($itineraries as $index => $itinerary) {
            $travelPackage->itineraries()->create([
                'title' => $itinerary['title'],
                'description' => $itinerary['description'],
                'breakfast' => $itinerary['meals_included']['breakfast'] ?? false,
                'lunch' => $itinerary['meals_included']['lunch'] ?? false,
                'dinner' => $itinerary['meals_included']['dinner'] ?? false,
                'snacks' => $itinerary['meals_included']['snacks'] ?? false,
                'destination_id' => $itinerary['destination_id'],
                'hotel_id' => $itinerary['hotel_id'],
                'boat_id' => $itinerary['boat_id'],
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function syncDatePrices(TravelPackage $travelPackage, array $datePrices): void
    {
        foreach ($datePrices as $row) {
            $datePrice = $travelPackage->datePrices()->create([
                'from_date' => $row['from_date'],
                'to_date' => $row['to_date'],
                'available_seats' => $row['available_seats'],
                'price' => $row['price'],
                'offer' => $row['offer'],
            ]);

            foreach ($row['accommodations'] as $accommodation) {
                $datePrice->accommodations()->create([
                    'hotel_id' => $accommodation['hotel_id'],
                    'room_id' => $accommodation['room_id'],
                ]);
            }
        }
    }
}
