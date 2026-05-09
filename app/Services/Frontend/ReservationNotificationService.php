<?php

namespace App\Services\Frontend;

use App\Models\Booking;
use App\Notifications\ReservationCreatedNotification;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;

class ReservationNotificationService
{
    public function notifyCreated(Booking $booking): void
    {
        $notification = new ReservationCreatedNotification($booking);

        Event::dispatch(new NotificationSent(
            $booking,
            $notification,
            'reservation'
        ));
    }
}
