<?php

namespace App\Listeners;

use App\Jobs\SendNotificationMailJob;
use App\Services\Dashboard\Admin\NotificationCenterService;
use Illuminate\Notifications\Events\NotificationSent;

class QueueNotificationMailListener
{
    public function __construct(
        private readonly NotificationCenterService $notificationCenterService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(NotificationSent $event): void
    {
        $payload = [
            'notification_class' => get_class($event->notification),
            'notifiable_type' => get_class($event->notifiable),
            'notifiable_id' => method_exists($event->notifiable, 'getKey')
                ? $event->notifiable->getKey()
                : null,
            'channel' => $event->channel,
            'data' => $this->extractNotificationData($event),
            'sent_at' => now()->toDateTimeString(),
        ];

        $this->notificationCenterService->storeFromEvent($event, $payload);

        SendNotificationMailJob::dispatch($payload);
    }

    /**
     * Extract notification payload in a safe way.
     */
    private function extractNotificationData(NotificationSent $event): array
    {
        if (method_exists($event->notification, 'toArray')) {
            return $event->notification->toArray($event->notifiable);
        }

        return [];
    }
}
