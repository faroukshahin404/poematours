<?php

namespace App\Services\Dashboard\Admin;

use App\Models\Setting;

class GeneralSettingsService
{
    private const NOTIFICATION_EMAILS_KEY = 'notification_emails';

    /**
     * @return array<int, string>
     */
    public function notificationEmails(): array
    {
        $rawValue = Setting::query()
            ->where('key', self::NOTIFICATION_EMAILS_KEY)
            ->value('value');

        if (! is_string($rawValue) || trim($rawValue) === '') {
            return [];
        }

        $decoded = json_decode($rawValue, true);
        if (! is_array($decoded)) {
            return [];
        }

        return collect($decoded)
            ->filter(fn ($email): bool => is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
            ->map(fn (string $email): string => mb_strtolower(trim($email)))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @param  array<int, string>  $emails
     */
    public function saveNotificationEmails(array $emails, int $adminId): void
    {
        $normalizedEmails = collect($emails)
            ->map(fn (string $email): string => mb_strtolower(trim($email)))
            ->filter(fn (string $email): bool => $email !== '')
            ->unique()
            ->values()
            ->all();

        Setting::query()->updateOrCreate(
            ['key' => self::NOTIFICATION_EMAILS_KEY],
            [
                'value' => json_encode($normalizedEmails, JSON_UNESCAPED_SLASHES),
                'created_by' => $adminId,
            ]
        );
    }
}
