<?php

namespace App\Services\Dashboard\Admin;

use App\Models\SystemNotification;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Log;
use Throwable;

class NotificationCenterService
{
    /**
     * Persist a sent notification event for admin center.
     *
     * @param  array<string, mixed>  $payload
     */
    public function storeFromEvent(NotificationSent $event, array $payload): SystemNotification
    {
        try {
            return SystemNotification::query()->create([
                'notification_class' => (string) ($payload['notification_class'] ?? get_class($event->notification)),
                'notifiable_type' => $payload['notifiable_type'] ?? get_class($event->notifiable),
                'notifiable_id' => $payload['notifiable_id'] ?? null,
                'channel' => $payload['channel'] ?? $event->channel,
                'data' => is_array($payload['data'] ?? null) ? $payload['data'] : [],
            ]);
        } catch (Throwable $exception) {
            Log::warning('Skipping notification persistence due to storage error.', [
                'error' => $exception->getMessage(),
            ]);

            return new SystemNotification([
                'notification_class' => (string) ($payload['notification_class'] ?? get_class($event->notification)),
                'notifiable_type' => $payload['notifiable_type'] ?? get_class($event->notifiable),
                'notifiable_id' => $payload['notifiable_id'] ?? null,
                'channel' => $payload['channel'] ?? $event->channel,
                'data' => is_array($payload['data'] ?? null) ? $payload['data'] : [],
                'is_read' => false,
            ]);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function latest(int $limit = 12): array
    {
        try {
            return SystemNotification::query()
                ->latest('id')
                ->limit($limit)
                ->get()
                ->map(fn (SystemNotification $notification): array => [
                    'id' => $notification->id,
                    'notification_class' => $notification->notification_class,
                    'channel' => $notification->channel,
                    'notifiable_type' => $notification->notifiable_type,
                    'notifiable_id' => $notification->notifiable_id,
                    'data' => $notification->data ?? [],
                    'is_read' => $notification->is_read,
                    'read_at' => $notification->read_at?->toDateTimeString(),
                    'created_at' => $notification->created_at?->toDateTimeString(),
                ])
                ->values()
                ->all();
        } catch (Throwable $exception) {
            Log::warning('Unable to fetch admin notifications.', [
                'error' => $exception->getMessage(),
            ]);

            return [];
        }
    }

    public function unreadCount(): int
    {
        try {
            return SystemNotification::query()
                ->where('is_read', false)
                ->count();
        } catch (Throwable $exception) {
            Log::warning('Unable to count unread admin notifications.', [
                'error' => $exception->getMessage(),
            ]);

            return 0;
        }
    }

    public function markAsRead(SystemNotification $notification): void
    {
        if ($notification->is_read) {
            return;
        }

        try {
            $notification->forceFill([
                'is_read' => true,
                'read_at' => now(),
            ])->save();
        } catch (Throwable $exception) {
            Log::warning('Unable to mark notification as read.', [
                'notification_id' => $notification->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function markAllAsRead(): void
    {
        try {
            SystemNotification::query()
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);
        } catch (Throwable $exception) {
            Log::warning('Unable to mark all notifications as read.', [
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
