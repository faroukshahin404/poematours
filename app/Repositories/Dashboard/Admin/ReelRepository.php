<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\ReelRepositoryInterface;
use App\Models\Reel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReelRepository implements ReelRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Reel::query()
            ->with(['creator:id,name', 'updater:id,name'])
            ->latest('id')
            ->paginate($perPage);
    }

    public function create(array $data, int $userId): Reel
    {
        return DB::transaction(function () use ($data, $userId) {
            $reel = new Reel;
            $reel->fill([
                'name' => $data['name'],
                'description' => $data['description'],
                'video_url' => $data['video_url'],
                'snapshot_url' => $data['snapshot_url'] ?? null,
                'created_by' => $userId,
            ]);
            $reel->save();

            return $reel->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    public function update(Reel $reel, array $data, int $userId): Reel
    {
        return DB::transaction(function () use ($reel, $data, $userId) {
            $oldVideoPath = $reel->getRawOriginal('video_url');
            $nextVideoPath = $data['video_url'] ?? $oldVideoPath;

            if ($oldVideoPath && $nextVideoPath !== $oldVideoPath) {
                Reel::deleteStoredVideo($oldVideoPath);
            }

            $oldSnapshotPath = $reel->getRawOriginal('snapshot_url');
            $nextSnapshotPath = array_key_exists('snapshot_url', $data)
                ? $data['snapshot_url']
                : $oldSnapshotPath;

            if ($oldSnapshotPath && $nextSnapshotPath !== $oldSnapshotPath) {
                Reel::deleteStoredSnapshot($oldSnapshotPath);
            }

            $reel->fill([
                'name' => $data['name'],
                'description' => $data['description'],
                'video_url' => $nextVideoPath,
                'snapshot_url' => $nextSnapshotPath,
                'updated_by' => $userId,
            ]);
            $reel->save();

            return $reel->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    public function delete(Reel $reel): void
    {
        DB::transaction(function () use ($reel): void {
            Reel::deleteStoredVideo($reel->getRawOriginal('video_url'));
            Reel::deleteStoredSnapshot($reel->getRawOriginal('snapshot_url'));
            $reel->delete();
        });
    }
}
