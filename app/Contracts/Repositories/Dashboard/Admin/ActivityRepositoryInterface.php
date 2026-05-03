<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\Activity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

interface ActivityRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * @param  array{destination_id: int, name: array<string, string>, description: array<string, string>}  $data
     */
    public function create(array $data, ?UploadedFile $image, int $userId): Activity;

    /**
     * @param  array{destination_id: int, name: array<string, string>, description: array<string, string>}  $data
     */
    public function update(Activity $activity, array $data, ?UploadedFile $image, int $userId): Activity;

    public function delete(Activity $activity, int $userId): void;
}
