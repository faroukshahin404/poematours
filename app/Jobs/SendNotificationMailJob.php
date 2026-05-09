<?php

namespace App\Jobs;

use App\Models\Setting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNotificationMailJob implements ShouldQueue
{
    use Queueable;

    private const FALLBACK_NOTIFICATION_EMAIL = 'faroukshahin30@gmail.com';

    private const NOTIFICATION_EMAILS_KEY = 'notification_emails';

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly array $notificationPayload
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $recipients = $this->resolveRecipients();
        if ($recipients === []) {
            Log::warning('Notification mail job skipped because no valid recipients were configured.');

            return;
        }

        $data = is_array($this->notificationPayload['data'] ?? null)
            ? $this->notificationPayload['data']
            : [];
        $title = (string) ($data['title'] ?? 'Notification Event');
        $messageText = (string) ($data['message'] ?? '');
        $customerName = (string) ($data['customer_name'] ?? '—');
        $customerEmail = (string) ($data['customer_email'] ?? '—');
        $submittedAt = (string) ($data['submitted_at'] ?? ($this->notificationPayload['sent_at'] ?? '—'));

        $html = sprintf(
            '<table cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; width: 100%%; max-width: 680px; font-family: Arial, sans-serif; font-size: 14px;">'
            .'<tr><th align="left" style="background:#f8fafc;">Title</th><td>%s</td></tr>'
            .'<tr><th align="left" style="background:#f8fafc;">Message</th><td>%s</td></tr>'
            .'<tr><th align="left" style="background:#f8fafc;">Customer Name</th><td>%s</td></tr>'
            .'<tr><th align="left" style="background:#f8fafc;">Customer Email</th><td>%s</td></tr>'
            .'<tr><th align="left" style="background:#f8fafc;">Submitted At</th><td>%s</td></tr>'
            .'</table>',
            e($title),
            e($messageText),
            e($customerName),
            e($customerEmail),
            e($submittedAt)
        );

        Mail::html(
            $html,
            function ($message) use ($recipients, $title): void {
                $message
                    ->to($recipients)
                    ->subject($title);
            }
        );
    }

    /**
     * @return array<int, string>
     */
    private function resolveRecipients(): array
    {
        $rawValue = Setting::query()
            ->where('key', self::NOTIFICATION_EMAILS_KEY)
            ->value('value');

        if (! is_string($rawValue) || trim($rawValue) === '') {
            return [self::FALLBACK_NOTIFICATION_EMAIL];
        }

        $decoded = json_decode($rawValue, true);
        if (! is_array($decoded)) {
            return [self::FALLBACK_NOTIFICATION_EMAIL];
        }

        $emails = collect($decoded)
            ->filter(fn ($email): bool => is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
            ->map(fn (string $email): string => mb_strtolower(trim($email)))
            ->unique()
            ->values()
            ->all();

        return $emails === [] ? [self::FALLBACK_NOTIFICATION_EMAIL] : $emails;
    }
}
