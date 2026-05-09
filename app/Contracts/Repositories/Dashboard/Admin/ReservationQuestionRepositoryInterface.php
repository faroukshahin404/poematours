<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\ReservationQuestion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReservationQuestionRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?ReservationQuestion;

    /**
     * @param  array{
     *     title: array<string, string>,
     *     description: array<string, string>,
     *     type: string,
     *     is_package_reservation: bool,
     *     is_reservation_page: bool,
     *     options: array<int, array{label: array<string, string>, added_price: float}>
     * }  $data
     */
    public function create(array $data, int $userId): ReservationQuestion;

    /**
     * @param  array{
     *     title: array<string, string>,
     *     description: array<string, string>,
     *     type: string,
     *     is_package_reservation: bool,
     *     is_reservation_page: bool,
     *     options: array<int, array{label: array<string, string>, added_price: float}>
     * }  $data
     */
    public function update(ReservationQuestion $question, array $data, int $userId): ReservationQuestion;

    public function delete(ReservationQuestion $question): void;
}
