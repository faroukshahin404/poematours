<?php

namespace App\Services\Dashboard\Admin;

use App\Models\Setting;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class PaymentSettingsService
{
    private const STRIPE_SETTINGS_KEY = 'payment_gateway_stripe';

    /**
     * @return array{
     *     enabled: bool,
     *     publishable_key: string|null,
     *     secret_key: string|null,
     *     webhook_secret: string|null,
     *     success_url: string|null,
     *     cancel_url: string|null,
     *     default_currency: string,
     *     has_secret_key: bool
     * }
     */
    public function stripeSettingsForForm(): array
    {
        $settings = $this->stripeSettingsForRuntime();

        return [
            'enabled' => (bool) ($settings['enabled'] ?? false),
            'publishable_key' => $settings['publishable_key'] ?? null,
            'secret_key' => null,
            'webhook_secret' => $settings['webhook_secret'] ?? null,
            'success_url' => $settings['success_url'] ?? null,
            'cancel_url' => $settings['cancel_url'] ?? null,
            'default_currency' => strtoupper((string) ($settings['default_currency'] ?? 'USD')),
            'has_secret_key' => ! empty($settings['secret_key']),
        ];
    }

    /**
     * @return array{
     *     enabled: bool,
     *     publishable_key: string|null,
     *     secret_key: string|null,
     *     webhook_secret: string|null,
     *     success_url: string|null,
     *     cancel_url: string|null,
     *     default_currency: string
     * }
     */
    public function stripeSettingsForRuntime(): array
    {
        $encrypted = Setting::query()
            ->where('key', self::STRIPE_SETTINGS_KEY)
            ->value('value');

        if (! is_string($encrypted) || trim($encrypted) === '') {
            return $this->defaultSettings();
        }

        try {
            $decrypted = Crypt::decryptString($encrypted);
            $decoded = json_decode($decrypted, true, flags: JSON_THROW_ON_ERROR);
        } catch (DecryptException|\JsonException) {
            return $this->defaultSettings();
        }

        if (! is_array($decoded)) {
            return $this->defaultSettings();
        }

        return array_merge($this->defaultSettings(), [
            'enabled' => (bool) ($decoded['enabled'] ?? false),
            'publishable_key' => $this->nullableString($decoded['publishable_key'] ?? null),
            'secret_key' => $this->nullableString($decoded['secret_key'] ?? null),
            'webhook_secret' => $this->nullableString($decoded['webhook_secret'] ?? null),
            'success_url' => $this->nullableString($decoded['success_url'] ?? null),
            'cancel_url' => $this->nullableString($decoded['cancel_url'] ?? null),
            'default_currency' => strtoupper((string) ($decoded['default_currency'] ?? 'USD')),
        ]);
    }

    /**
     * @param  array{
     *     enabled: bool,
     *     publishable_key: string|null,
     *     secret_key: string|null,
     *     webhook_secret: string|null,
     *     success_url: string,
     *     cancel_url: string,
     *     default_currency: string
     * }  $payload
     */
    public function saveStripeSettings(array $payload, int $adminId): void
    {
        $current = $this->stripeSettingsForRuntime();
        $providedSecret = $this->nullableString($payload['secret_key'] ?? null);

        $normalized = [
            'enabled' => (bool) ($payload['enabled'] ?? false),
            'publishable_key' => $this->nullableString($payload['publishable_key'] ?? null),
            'secret_key' => $providedSecret ?? $current['secret_key'],
            'webhook_secret' => $this->nullableString($payload['webhook_secret'] ?? null),
            'success_url' => trim((string) ($payload['success_url'] ?? '')),
            'cancel_url' => trim((string) ($payload['cancel_url'] ?? '')),
            'default_currency' => strtoupper(trim((string) ($payload['default_currency'] ?? 'USD'))),
        ];

        $encrypted = Crypt::encryptString(json_encode($normalized, JSON_THROW_ON_ERROR));

        Setting::query()->updateOrCreate(
            ['key' => self::STRIPE_SETTINGS_KEY],
            [
                'value' => $encrypted,
                'created_by' => $adminId,
            ]
        );
    }

    /**
     * @return array{
     *     enabled: bool,
     *     publishable_key: string|null,
     *     secret_key: string|null,
     *     webhook_secret: string|null,
     *     success_url: string|null,
     *     cancel_url: string|null,
     *     default_currency: string
     * }
     */
    private function defaultSettings(): array
    {
        return [
            'enabled' => false,
            'publishable_key' => null,
            'secret_key' => null,
            'webhook_secret' => null,
            'success_url' => null,
            'cancel_url' => null,
            'default_currency' => 'USD',
        ];
    }

    private function nullableString(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
}
