<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\ReservationRepositoryInterface;
use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReservationRepository implements ReservationRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Booking::query()
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * {@inheritdoc}
     */
    public function findOrFail(int $id): Booking
    {
        return Booking::query()
            ->with(['travellers' => fn ($query) => $query->orderBy('sort_order')])
            ->findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function updateStatus(
        Booking $booking,
        string $bookingStatus,
        string $paymentStatus,
        float $paidAmount,
        ?float $totalAmount
    ): Booking {
        return DB::transaction(function () use ($booking, $bookingStatus, $paymentStatus, $paidAmount, $totalAmount): Booking {
            $booking->booking_status = $bookingStatus;
            $booking->payment_status = $paymentStatus;
            $booking->paid_amount = round($paidAmount, 2);
            $booking->total_amount = $totalAmount !== null ? round($totalAmount, 2) : null;
            $booking->save();

            return $booking->fresh(['travellers']);
        });
    }
}
