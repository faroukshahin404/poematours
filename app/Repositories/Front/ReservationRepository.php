<?php

namespace App\Repositories\Front;

use App\Contracts\Repositories\Front\ReservationRepositoryInterface;
use App\Models\Booking;
use App\Models\BookingTraveller;
use App\Models\ReservationQuestion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ReservationRepository implements ReservationRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function reservationPageQuestions(): array
    {
        return ReservationQuestion::query()
            ->where('is_reservation_page', true)
            ->orderBy('id')
            ->get()
            ->all();
    }

    public function packageReservationQuestions(): array
    {
        return ReservationQuestion::query()
            ->where('is_package_reservation', true)
            ->orderBy('id')
            ->get()
            ->all();
    }

    /**
     * {@inheritdoc}
     */
    public function createBooking(array $payload, array $travellers, array $travellerPassportFiles): Booking
    {
        return DB::transaction(function () use ($payload, $travellers, $travellerPassportFiles): Booking {
            $booking = Booking::query()->create($payload);

            foreach ($travellers as $index => $traveller) {
                $photoPath = null;
                $file = $travellerPassportFiles[$index] ?? null;
                if ($file instanceof UploadedFile) {
                    $photoPath = $file->store('bookings/passports', 'public');
                }

                BookingTraveller::query()->create([
                    'booking_id' => $booking->id,
                    'sort_order' => (int) ($traveller['sort_order'] ?? ($index + 1)),
                    'first_name_on_passport' => $traveller['first_name_on_passport'] ?? null,
                    'middle_name_on_passport' => $traveller['middle_name_on_passport'] ?? null,
                    'last_name_on_passport' => $traveller['last_name_on_passport'] ?? null,
                    'gender' => $traveller['gender'] ?? null,
                    'birthdate' => $traveller['birthdate'] ?? null,
                    'passport_country' => $traveller['passport_country'] ?? null,
                    'passport_number' => $traveller['passport_number'] ?? null,
                    'passport_expiration_date' => $traveller['passport_expiration_date'] ?? null,
                    'country_of_birth' => $traveller['country_of_birth'] ?? null,
                    'father_first_name' => $traveller['father_first_name'] ?? null,
                    'passport_photo_path' => $photoPath,
                ]);
            }

            return $booking->fresh(['travellers']);
        });
    }
}
