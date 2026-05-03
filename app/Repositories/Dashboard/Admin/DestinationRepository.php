<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\DestinationRepositoryInterface;
use App\Models\Destination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class DestinationRepository implements DestinationRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Destination::query()
            ->with(['creator:id,name', 'updater:id,name'])
            ->orderBy('slug')
            ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?Destination
    {
        return Destination::query()->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data, ?UploadedFile $image, int $userId): Destination
    {
        return DB::transaction(function () use ($data, $image, $userId) {
            $slug = Destination::generateUniqueSlug($data['slug']);
            $imagePath = $image !== null ? Destination::storeImage($image) : null;

            $destination = new Destination;
            $destination->setAttribute('name', $data['name']);
            $destination->setAttribute('slug', $slug);
            $destination->setAttribute('image', $imagePath);
            $destination->setAttribute('lat', $data['lat'] ?? null);
            $destination->setAttribute('lng', $data['lng'] ?? null);
            $destination->setAttribute('created_by', $userId);
            $destination->save();

            return $destination->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function update(Destination $destination, array $data, ?UploadedFile $image, int $userId): Destination
    {
        return DB::transaction(function () use ($destination, $data, $image, $userId) {
            $slug = Destination::generateUniqueSlug($data['slug'], $destination->id);

            if ($image !== null) {
                Destination::deleteStoredImage($destination->getRawOriginal('image'));
                $destination->setAttribute('image', Destination::storeImage($image));
            }

            $destination->setAttribute('name', $data['name']);
            $destination->setAttribute('slug', $slug);
            $destination->setAttribute('lat', $data['lat'] ?? null);
            $destination->setAttribute('lng', $data['lng'] ?? null);
            $destination->setAttribute('updated_by', $userId);
            $destination->save();

            return $destination->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Destination $destination, int $userId): void
    {
        DB::transaction(function () use ($destination): void {
            Destination::deleteStoredImage($destination->getRawOriginal('image'));
            $destination->delete();
        });
    }
}
