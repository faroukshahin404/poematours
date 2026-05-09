<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReservationRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function findOrFail(int $id): Booking;

    public function updateStatus(
        Booking $booking,
        string $bookingStatus,
        string $paymentStatus,
        float $paidAmount,
        ?float $totalAmount
    ): Booking;
}
