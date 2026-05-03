<?php

namespace App\Services\Frontend;

use App\Models\Reservation;
use App\Services\Payments\StripePaymentIntentService;
use App\Services\Settings\SettingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReservationService
{
    public function __construct(
        private readonly SettingService $settingService,
        private readonly StripePaymentIntentService $stripePaymentIntentService,
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function createPaymentIntent(array $payload): array
    {
        $paymentSettings = $this->settingService->paymentSettings();
        $this->guardPaymentSettings($paymentSettings);

        $addonGroups = $this->settingService->activeReservationAddonGroups();
        $pricing = $this->calculatePricing($payload, $addonGroups, (string) $paymentSettings['currency']);

        $reservation = DB::transaction(function () use ($payload, $pricing, $paymentSettings): Reservation {
            return Reservation::query()->create([
                'uuid' => (string) Str::uuid(),
                'full_name' => (string) $payload['full_name'],
                'email' => (string) $payload['email'],
                'phone' => $payload['phone'] ?? null,
                'country' => $payload['country'] ?? null,
                'preferred_contact_method' => $payload['preferred_contact_method'] ?? null,
                'adults' => (int) ($payload['adults'] ?? 1),
                'children' => (int) ($payload['children'] ?? 0),
                'arrival_date' => $payload['arrival_date'] ?? null,
                'departure_date' => $payload['departure_date'] ?? null,
                'duration_days' => $payload['duration_days'] ?? null,
                'destinations' => $payload['destinations'] ?? null,
                'tour_style' => $payload['tour_style'] ?? null,
                'hotel_category' => $payload['hotel_category'] ?? null,
                'need_transfers' => (bool) ($payload['need_transfers'] ?? false),
                'need_domestic_flights' => (bool) ($payload['need_domestic_flights'] ?? false),
                'selected_addons' => $pricing['selected_addons'],
                'addons_breakdown' => $pricing['addons_breakdown'],
                'addons_total' => $pricing['addons_total'],
                'base_total' => $pricing['base_total'],
                'estimated_total' => $pricing['estimated_total'],
                'deposit_percentage' => $pricing['deposit_percentage'],
                'deposit_amount' => $pricing['deposit_amount'],
                'currency' => $pricing['currency'],
                'notes' => $payload['notes'] ?? null,
                'payment_status' => 'pending',
                'status' => 'pending_payment',
            ]);
        });

        $intent = $this->stripePaymentIntentService->create(
            (string) $paymentSettings['secret_key'],
            (int) $pricing['deposit_amount'],
            (string) $pricing['currency'],
            [
                'reservation_uuid' => $reservation->uuid,
            ],
        );

        $reservation->update([
            'stripe_payment_intent_id' => $intent['id'],
        ]);

        return [
            'reservation_uuid' => $reservation->uuid,
            'client_secret' => $intent['client_secret'],
            'publishable_key' => (string) $paymentSettings['publishable_key'],
            'currency' => (string) $pricing['currency'],
            'deposit_amount' => (int) $pricing['deposit_amount'],
            'deposit_percentage' => (int) $pricing['deposit_percentage'],
            'estimated_total' => (int) $pricing['estimated_total'],
            'addons_total' => (int) $pricing['addons_total'],
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<int, array<string, mixed>>  $addonGroups
     * @return array<string, mixed>
     */
    public function calculatePricing(array $payload, array $addonGroups, string $currency): array
    {
        $adults = (int) ($payload['adults'] ?? 1);
        $children = (int) ($payload['children'] ?? 0);
        $travelers = max(1, $adults + $children);
        $baseTotal = $this->toMinor((float) ($payload['estimated_total'] ?? 0));

        $addonsSelection = is_array($payload['addons'] ?? null) ? $payload['addons'] : [];
        $addonsTotal = 0;
        $breakdown = [];
        $normalizedSelected = [];

        foreach ($addonGroups as $group) {
            $groupCode = (string) ($group['code'] ?? '');
            if ($groupCode === '') {
                continue;
            }

            $selectedForGroup = $addonsSelection[$groupCode] ?? null;
            $selectedCodes = is_array($selectedForGroup) ? array_values($selectedForGroup) : (is_string($selectedForGroup) ? [$selectedForGroup] : []);
            $normalizedSelected[$groupCode] = $selectedCodes;

            $optionMap = [];
            foreach ((array) ($group['options'] ?? []) as $option) {
                if (! is_array($option)) {
                    continue;
                }
                $optionCode = (string) ($option['code'] ?? '');
                if ($optionCode !== '') {
                    $optionMap[$optionCode] = $option;
                }
            }

            foreach ($selectedCodes as $selectedCode) {
                if (! isset($optionMap[$selectedCode])) {
                    continue;
                }

                $option = $optionMap[$selectedCode];
                $unitMinor = $this->toMinor((float) ($option['price'] ?? 0));
                $priceType = (string) ($option['price_type'] ?? 'flat');
                $quantity = $priceType === 'per_person' ? $travelers : 1;
                $lineSubtotal = $unitMinor * $quantity;
                $addonsTotal += $lineSubtotal;

                $breakdown[] = [
                    'group_code' => $groupCode,
                    'group_title' => (string) ($group['title'] ?? $groupCode),
                    'option_code' => (string) $selectedCode,
                    'option_label' => (string) ($option['label'] ?? $selectedCode),
                    'price_type' => $priceType,
                    'quantity' => $quantity,
                    'unit_price' => $unitMinor,
                    'subtotal' => $lineSubtotal,
                ];
            }
        }

        $estimatedTotal = $baseTotal + $addonsTotal;
        $depositPercentage = max(1, min((int) $this->settingService->get(SettingService::KEY_RESERVATION_DEPOSIT_PERCENTAGE, 20), 100));
        $depositAmount = (int) round($estimatedTotal * ($depositPercentage / 100));

        return [
            'selected_addons' => $normalizedSelected,
            'addons_breakdown' => $breakdown,
            'addons_total' => $addonsTotal,
            'base_total' => $baseTotal,
            'estimated_total' => $estimatedTotal,
            'deposit_percentage' => $depositPercentage,
            'deposit_amount' => max(1, $depositAmount),
            'currency' => strtoupper($currency),
        ];
    }

    /**
     * @param  array<string, mixed>  $settings
     */
    private function guardPaymentSettings(array $settings): void
    {
        if (! ($settings['enabled'] ?? false)) {
            throw ValidationException::withMessages([
                'payment' => 'Online payment is currently unavailable. Please contact support.',
            ]);
        }

        if (($settings['publishable_key'] ?? '') === '' || ($settings['secret_key'] ?? '') === '') {
            throw ValidationException::withMessages([
                'payment' => 'Payment settings are incomplete. Please contact support.',
            ]);
        }
    }

    private function toMinor(float $value): int
    {
        return (int) round(max(0, $value) * 100);
    }
}
