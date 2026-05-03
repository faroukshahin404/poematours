<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\TravelPackage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

interface TravelPackageRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * @param  array{
     *     slug: string,
     *     title: array<string, string>,
     *     description: array<string, string>,
     *     details: array<string, mixed>,
     *     package_category_ids: array<int, int>,
     *     package_label_group_ids: array<int, int>,
     *     package_label_ids: array<int, int>,
     *     activity_ids: array<int, int>,
     *     itineraries: array<int, array{
     *         title: string,
     *         description: string|null,
     *         meals_included: array<string, bool>,
     *         destination_id: int,
     *         hotel_id: int|null,
     *         boat_id: int|null
     *     }>,
     *     date_prices: array<int, array{
     *         from_date: string,
     *         to_date: string,
     *         available_seats: int,
     *         price: float,
     *         offer: float|null,
     *         accommodations: array<int, array{hotel_id: int, room_id: int}>
     *     }>,
     *     remove_media_ids: array<int, int>,
     *     remove_pdf: bool
     * }  $data
     * @param  array<int, UploadedFile>  $images
     */
    public function create(array $data, ?UploadedFile $pdf, array $images, int $userId): TravelPackage;

    /**
     * @param  array{
     *     slug: string,
     *     title: array<string, string>,
     *     description: array<string, string>,
     *     details: array<string, mixed>,
     *     package_category_ids: array<int, int>,
     *     package_label_group_ids: array<int, int>,
     *     package_label_ids: array<int, int>,
     *     activity_ids: array<int, int>,
     *     itineraries: array<int, array{
     *         title: string,
     *         description: string|null,
     *         meals_included: array<string, bool>,
     *         destination_id: int,
     *         hotel_id: int|null,
     *         boat_id: int|null
     *     }>,
     *     date_prices: array<int, array{
     *         from_date: string,
     *         to_date: string,
     *         available_seats: int,
     *         price: float,
     *         offer: float|null,
     *         accommodations: array<int, array{hotel_id: int, room_id: int}>
     *     }>,
     *     remove_media_ids: array<int, int>,
     *     remove_pdf: bool
     * }  $data
     * @param  array<int, UploadedFile>  $images
     */
    public function update(TravelPackage $travelPackage, array $data, ?UploadedFile $pdf, array $images, int $userId): TravelPackage;

    public function duplicate(TravelPackage $travelPackage, int $userId): TravelPackage;

    public function delete(TravelPackage $travelPackage, int $userId): void;
}
