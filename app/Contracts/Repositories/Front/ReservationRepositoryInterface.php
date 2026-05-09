<?php

namespace App\Contracts\Repositories\Front;

use App\Models\Booking;
use App\Models\ReservationQuestion;
use Illuminate\Http\UploadedFile;

interface ReservationRepositoryInterface
{
    /**
     * @return array<int, ReservationQuestion>
     */
    public function reservationPageQuestions(): array;

    /**
     * @return array<int, ReservationQuestion>
     */
    public function packageReservationQuestions(): array;

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<int, array{sort_order:int,first_name_on_passport:?string,middle_name_on_passport:?string,last_name_on_passport:?string,gender:?string,birthdate:?string,passport_country:?string,passport_number:?string,passport_expiration_date:?string,country_of_birth:?string,father_first_name:?string}>  $travellers
     * @param  array<int, UploadedFile|null>  $travellerPassportFiles
     */
    public function createBooking(array $payload, array $travellers, array $travellerPassportFiles): Booking;
}
