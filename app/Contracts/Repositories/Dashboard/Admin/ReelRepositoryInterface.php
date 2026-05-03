<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\Reel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReelRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * @param  array{name: array<string, string>, description: array<string, string>, video_url: string}  $data
     */
    public function create(array $data, int $userId): Reel;

    /**
     * @param  array{name: array<string, string>, description: array<string, string>, video_url: string|null}  $data
     */
    public function update(Reel $reel, array $data, int $userId): Reel;

    public function delete(Reel $reel): void;
}
