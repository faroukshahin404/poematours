<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReservationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'booking_status' => ['required', 'string', Rule::in(Booking::BOOKING_STATUSES)],
            'payment_status' => ['required', 'string', Rule::in(Booking::PAYMENT_STATUSES)],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
