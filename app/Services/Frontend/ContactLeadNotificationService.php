<?php

namespace App\Services\Frontend;

use App\Models\CrmContact;
use App\Notifications\WebsiteContactCreatedNotification;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;

class ContactLeadNotificationService
{
    public function notifyCreated(CrmContact $contact): void
    {
        $notification = new WebsiteContactCreatedNotification($contact);

        Event::dispatch(new NotificationSent(
            $contact,
            $notification,
            'website-contact'
        ));
    }
}
