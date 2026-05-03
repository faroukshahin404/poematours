<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\ActivityRepositoryInterface;
use App\Models\Activity;
use App\Models\Language;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Activity::query()
            ->with(['destination:id,name,slug', 'creator:id,name', 'updater:id,name'])
            ->withCount('packages')
            ->orderBy('slug')
            ->paginate($perPage);
    }

    public function create(array $data, ?UploadedFile $image, int $userId): Activity
    {
        return DB::transaction(function () use ($data, $image, $userId) {
            $slug = Activity::generateUniqueSlug($this->defaultNameValue($data['name']));

            $activity = new Activity;
            $activity->setAttribute('name', $data['name']);
            $activity->setAttribute('description', $data['description']);
            $activity->destination_id = $data['destination_id'];
            $activity->slug = $slug;
            $activity->image = $image ? Activity::storeImage($image) : null;
            $activity->created_by = $userId;
            $activity->save();

            return $activity->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    public function update(Activity $activity, array $data, ?UploadedFile $image, int $userId): Activity
    {
        return DB::transaction(function () use ($activity, $data, $image, $userId) {
            $slug = Activity::generateUniqueSlug($this->defaultNameValue($data['name']), $activity->id);

            if ($image) {
                Activity::deleteStoredImage($activity->getRawOriginal('image'));
                $activity->image = Activity::storeImage($image);
            }

            $activity->setAttribute('name', $data['name']);
            $activity->setAttribute('description', $data['description']);
            $activity->destination_id = $data['destination_id'];
            $activity->slug = $slug;
            $activity->updated_by = $userId;
            $activity->save();

            return $activity->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    public function delete(Activity $activity, int $userId): void
    {
        DB::transaction(function () use ($activity): void {
            Activity::deleteStoredImage($activity->getRawOriginal('image'));
            $activity->delete();
        });
    }

    /**
     * @param  array<string, string>  $name
     */
    private function defaultNameValue(array $name): string
    {
        $default = Language::defaultSlug();
        $value = trim((string) ($name[$default] ?? ''));

        if ($value !== '') {
            return $value;
        }

        return trim((string) (reset($name) ?: 'activity'));
    }
}
