<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\BoatRepositoryInterface;
use App\Enums\StoragePath;
use App\Models\Boat;
use App\Services\Media\MediaUploadService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class BoatRepository implements BoatRepositoryInterface
{
    public function __construct(
        private readonly MediaUploadService $mediaUploadService
    ) {}

    /**
     * {@inheritdoc}
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Boat::query()
            ->with(['media:id,path,model_type,model_id', 'creator:id,name', 'updater:id,name'])
            ->orderBy('slug')
            ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?Boat
    {
        return Boat::query()->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data, ?UploadedFile $boatImage, ?UploadedFile $suiteImage, ?UploadedFile $foodImage, ?UploadedFile $wellnessImage, array $images, int $userId): Boat
    {
        return DB::transaction(function () use ($data, $boatImage, $suiteImage, $foodImage, $wellnessImage, $images, $userId) {
            $slug = Boat::generateUniqueSlug($data['slug']);

            $boat = new Boat;
            $boat->name = $data['name'];
            $boat->slug = $slug;
            $boat->boat = $data['boat'];
            $boat->suite = $data['suite'];
            $boat->food = $data['food'];
            $boat->wellness = $data['wellness'];
            $boat->boat_image = $boatImage ? Boat::storeSectionImage($boatImage, 'boat') : null;
            $boat->suite_image = $suiteImage ? Boat::storeSectionImage($suiteImage, 'suite') : null;
            $boat->food_image = $foodImage ? Boat::storeSectionImage($foodImage, 'food') : null;
            $boat->wellness_image = $wellnessImage ? Boat::storeSectionImage($wellnessImage, 'wellness') : null;
            $boat->created_by = $userId;
            $boat->save();

            $this->mediaUploadService->uploadForModel($boat, StoragePath::Boats, $images, $userId);

            return $boat->fresh(['media:id,path,model_type,model_id', 'creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function update(Boat $boat, array $data, ?UploadedFile $boatImage, ?UploadedFile $suiteImage, ?UploadedFile $foodImage, ?UploadedFile $wellnessImage, array $images, int $userId): Boat
    {
        return DB::transaction(function () use ($boat, $data, $boatImage, $suiteImage, $foodImage, $wellnessImage, $images, $userId) {
            $slug = Boat::generateUniqueSlug($data['slug'], $boat->id);

            if ($data['remove_boat_image']) {
                Boat::deleteStoredImage($boat->getRawOriginal('boat_image'));
                $boat->boat_image = null;
            }
            if ($data['remove_suite_image']) {
                Boat::deleteStoredImage($boat->getRawOriginal('suite_image'));
                $boat->suite_image = null;
            }
            if ($data['remove_food_image']) {
                Boat::deleteStoredImage($boat->getRawOriginal('food_image'));
                $boat->food_image = null;
            }
            if ($data['remove_wellness_image']) {
                Boat::deleteStoredImage($boat->getRawOriginal('wellness_image'));
                $boat->wellness_image = null;
            }

            if ($boatImage) {
                Boat::deleteStoredImage($boat->getRawOriginal('boat_image'));
                $boat->boat_image = Boat::storeSectionImage($boatImage, 'boat');
            }
            if ($suiteImage) {
                Boat::deleteStoredImage($boat->getRawOriginal('suite_image'));
                $boat->suite_image = Boat::storeSectionImage($suiteImage, 'suite');
            }
            if ($foodImage) {
                Boat::deleteStoredImage($boat->getRawOriginal('food_image'));
                $boat->food_image = Boat::storeSectionImage($foodImage, 'food');
            }
            if ($wellnessImage) {
                Boat::deleteStoredImage($boat->getRawOriginal('wellness_image'));
                $boat->wellness_image = Boat::storeSectionImage($wellnessImage, 'wellness');
            }

            $this->mediaUploadService->deleteByIdsForModel($boat, $data['remove_media_ids']);
            $this->mediaUploadService->uploadForModel($boat, StoragePath::Boats, $images, $userId);

            $boat->name = $data['name'];
            $boat->slug = $slug;
            $boat->boat = $data['boat'];
            $boat->suite = $data['suite'];
            $boat->food = $data['food'];
            $boat->wellness = $data['wellness'];
            $boat->updated_by = $userId;
            $boat->save();

            return $boat->fresh(['media:id,path,model_type,model_id', 'creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Boat $boat, int $userId): void
    {
        DB::transaction(function () use ($boat): void {
            $this->mediaUploadService->deleteAllForModel($boat);
            Boat::deleteStoredImage($boat->getRawOriginal('boat_image'));
            Boat::deleteStoredImage($boat->getRawOriginal('suite_image'));
            Boat::deleteStoredImage($boat->getRawOriginal('food_image'));
            Boat::deleteStoredImage($boat->getRawOriginal('wellness_image'));
            $boat->delete();
        });
    }
}
