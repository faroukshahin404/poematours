<?php

namespace App\Services\Settings;

use App\Models\Setting;

class SettingService
{
    public const KEY_STRIPE_PUBLISHABLE = 'stripe_publishable_key';
    public const KEY_STRIPE_SECRET = 'stripe_secret_key';
    public const KEY_STRIPE_WEBHOOK = 'stripe_webhook_secret';
    public const KEY_STRIPE_MODE = 'stripe_mode';
    public const KEY_STRIPE_ENABLED = 'stripe_enabled';
    public const KEY_RESERVATION_CURRENCY = 'reservation_currency';
    public const KEY_RESERVATION_DEPOSIT_PERCENTAGE = 'reservation_deposit_percentage';
    public const KEY_RESERVATION_ADDON_GROUPS = 'reservation_addon_groups';

    /**
     * @return array<string, mixed>
     */
    public function paymentSettings(): array
    {
        return [
            'publishable_key' => (string) $this->get(self::KEY_STRIPE_PUBLISHABLE, ''),
            'secret_key' => (string) $this->get(self::KEY_STRIPE_SECRET, ''),
            'webhook_secret' => (string) $this->get(self::KEY_STRIPE_WEBHOOK, ''),
            'mode' => (string) $this->get(self::KEY_STRIPE_MODE, 'test'),
            'enabled' => (bool) $this->get(self::KEY_STRIPE_ENABLED, false),
            'currency' => strtoupper((string) $this->get(self::KEY_RESERVATION_CURRENCY, 'USD')),
            'deposit_percentage' => (int) $this->get(self::KEY_RESERVATION_DEPOSIT_PERCENTAGE, 20),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function reservationAddonGroups(): array
    {
        $groups = $this->get(self::KEY_RESERVATION_ADDON_GROUPS, []);

        return is_array($groups) ? array_values($groups) : [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function activeReservationAddonGroups(): array
    {
        $groups = [];

        foreach ($this->reservationAddonGroups() as $group) {
            if (! is_array($group)) {
                continue;
            }

            $options = [];
            foreach ((array) ($group['options'] ?? []) as $option) {
                if (! is_array($option)) {
                    continue;
                }

                if (! ($option['is_active'] ?? true)) {
                    continue;
                }

                $options[] = $option;
            }

            if ($options === []) {
                continue;
            }

            $group['options'] = array_values($options);
            $groups[] = $group;
        }

        usort($groups, fn (array $a, array $b): int => ((int) ($a['sort_order'] ?? 0)) <=> ((int) ($b['sort_order'] ?? 0)));

        return $groups;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $setting = Setting::query()->where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return $this->decodeValue($setting->value, $default);
    }

    public function put(string $key, mixed $value): void
    {
        Setting::query()->updateOrCreate(['key' => $key], ['value' => $this->encodeValue($value)]);
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public function putMany(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->put($key, $value);
        }
    }

    private function encodeValue(mixed $value): string
    {
        $encoded = json_encode($value);

        return $encoded === false ? 'null' : $encoded;
    }

    private function decodeValue(mixed $value, mixed $default = null): mixed
    {
        if (! is_string($value)) {
            return $default;
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        // Backward compatibility for old plain string encrypted values.
        return $value;
    }
}
