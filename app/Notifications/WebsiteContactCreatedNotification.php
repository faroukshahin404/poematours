<?php

namespace App\Notifications;

use App\Models\CrmContact;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WebsiteContactCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly CrmContact $contact
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
            'title' => 'New website contact lead',
            'message' => sprintf(
                'CRM contact #%d created from website by %s (%s).',
                $this->contact->id,
                (string) $this->contact->name,
                (string) $this->contact->email
            ),
            'contact_id' => $this->contact->id,
            'customer_name' => (string) $this->contact->name,
            'customer_email' => (string) ($this->contact->email ?? ''),
            'submitted_at' => $this->contact->created_at?->toDateTimeString(),
            'source' => (string) $this->contact->source,
        ];
    }
}
