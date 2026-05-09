<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservationCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Booking $booking
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New reservation submitted',
            'message' => sprintf(
                'Reservation #%d created by %s %s (%s).',
                $this->booking->id,
                (string) $this->booking->first_name,
                (string) $this->booking->last_name,
                (string) $this->booking->email
            ),
            'booking_id' => $this->booking->id,
            'travellers_count' => (int) $this->booking->travellers_count,
            'customer_name' => trim(
                (string) $this->booking->first_name.' '.(string) $this->booking->last_name
            ),
            'customer_email' => $this->booking->email,
            'submitted_at' => $this->booking->created_at?->toDateTimeString(),
        ];
    }
}
