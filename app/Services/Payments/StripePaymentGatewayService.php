<?php

namespace App\Services\Payments;

use App\Models\PaymentTransaction;
use App\Services\Dashboard\Admin\PaymentSettingsService;
use Illuminate\Support\Str;
use RuntimeException;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripePaymentGatewayService
{
    public function __construct(
        private readonly PaymentSettingsService $paymentSettingsService
    ) {}

    /**
     * @param  array{
     *     full_name?: string|null,
     *     email?: string|null,
     *     phone?: string|null
     * }  $clientInfo
     * @param  array<string, string|int|float|bool|null>  $metadata
     * @return array{
     *     payment_link: string,
     *     payment_key: string,
     *     stripe_session_id: string
     * }
     */
    public function createPaymentLink(
        float $amount,
        array $clientInfo,
        float $paidAmount,
        string $currency,
        array $metadata = [],
    ): array {
        $settings = $this->paymentSettingsService->stripeSettingsForRuntime();
        $secretKey = $settings['secret_key'] ?? null;

        if (! ($settings['enabled'] ?? false) || ! is_string($secretKey) || trim($secretKey) === '') {
            throw new RuntimeException('Stripe gateway is not configured or disabled.');
        }

        $currencyCode = strtoupper(trim($currency));
        $remainingAmount = $amount - $paidAmount;

        if ($remainingAmount <= 0) {
            throw new RuntimeException('Charge amount must be greater than zero.');
        }

        $totalAmountMinor = $this->toMinorUnits($amount);
        $paidAmountMinor = $this->toMinorUnits($paidAmount);
        $chargeAmountMinor = $this->toMinorUnits($remainingAmount);
        $paymentKey = (string) Str::ulid();
        $builtMetadata = $this->buildMetadata($paymentKey, $amount, $paidAmount, $metadata);

        $stripe = new StripeClient($secretKey);
        $successUrl = $this->assertHttpsUrl((string) ($settings['success_url'] ?? ''));
        $cancelUrl = $this->assertHttpsUrl((string) ($settings['cancel_url'] ?? ''));
        $email = $this->sanitizeEmail($clientInfo['email'] ?? null);
        $name = $this->sanitizeString($clientInfo['full_name'] ?? null, 255);

        try {
            /** @var Session $session */
            $session = $stripe->checkout->sessions->create([
                'mode' => 'payment',
                'success_url' => $successUrl.'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                'client_reference_id' => $paymentKey,
                'customer_email' => $email,
                'line_items' => [[
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => strtolower($currencyCode),
                        'unit_amount' => $chargeAmountMinor,
                        'product_data' => [
                            'name' => 'Poema Tours Reservation Payment',
                            'description' => sprintf('Amount due after paid %.2f', $paidAmount),
                        ],
                    ],
                ]],
                'metadata' => $builtMetadata,
                'payment_intent_data' => [
                    'metadata' => $builtMetadata,
                ],
            ]);
        } catch (ApiErrorException $exception) {
            throw new RuntimeException('Unable to create Stripe checkout session.', previous: $exception);
        }

        if (! is_string($session->url) || $session->url === '') {
            throw new RuntimeException('Stripe returned an invalid payment URL.');
        }

        PaymentTransaction::query()->create([
            'payment_key' => $paymentKey,
            'stripe_session_id' => (string) $session->id,
            'currency' => $currencyCode,
            'total_amount_minor' => $totalAmountMinor,
            'paid_amount_minor' => $paidAmountMinor,
            'charge_amount_minor' => $chargeAmountMinor,
            'status' => (string) ($session->payment_status ?? 'unpaid'),
            'payment_link' => $session->url,
            'client_info' => [
                'full_name' => $name,
                'email' => $email,
                'phone' => $this->sanitizeString($clientInfo['phone'] ?? null, 100),
            ],
            'gateway_payload' => [
                'session_status' => $session->status,
                'payment_status' => $session->payment_status,
                'metadata' => $builtMetadata,
            ],
        ]);

        return [
            'payment_link' => $session->url,
            'payment_key' => $paymentKey,
            'stripe_session_id' => (string) $session->id,
        ];
    }

    /**
     * @return array{
     *     payment_key: string,
     *     stripe_session_id: string,
     *     status: string,
     *     payment_status: string,
     *     currency: string,
     *     amount_total: float,
     *     amount_received: float,
     *     payment_link: string|null
     * }
     */
    public function getPaymentStatus(string $paymentKey): array
    {
        $settings = $this->paymentSettingsService->stripeSettingsForRuntime();
        $secretKey = $settings['secret_key'] ?? null;

        if (! ($settings['enabled'] ?? false) || ! is_string($secretKey) || trim($secretKey) === '') {
            throw new RuntimeException('Stripe gateway is not configured or disabled.');
        }

        $transaction = PaymentTransaction::query()
            ->where('payment_key', $paymentKey)
            ->first();

        if (! $transaction instanceof PaymentTransaction) {
            throw new RuntimeException('Payment key was not found.');
        }

        $stripe = new StripeClient($secretKey);

        try {
            /** @var Session $session */
            $session = $stripe->checkout->sessions->retrieve($transaction->stripe_session_id, []);
        } catch (ApiErrorException $exception) {
            throw new RuntimeException('Unable to retrieve Stripe checkout status.', previous: $exception);
        }

        $existingGatewayPayload = is_array($transaction->gateway_payload) ? $transaction->gateway_payload : [];

        $transaction->update([
            'status' => (string) ($session->payment_status ?? $transaction->status),
            'gateway_payload' => [
                ...$existingGatewayPayload,
                'session_status' => $session->status,
                'payment_status' => $session->payment_status,
            ],
        ]);

        return [
            'payment_key' => $transaction->payment_key,
            'stripe_session_id' => $transaction->stripe_session_id,
            'status' => (string) ($session->status ?? 'unknown'),
            'payment_status' => (string) ($session->payment_status ?? 'unpaid'),
            'currency' => strtoupper((string) ($session->currency ?? $transaction->currency)),
            'amount_total' => $this->fromMinorUnits((int) ($session->amount_total ?? $transaction->charge_amount_minor)),
            'amount_received' => $this->fromMinorUnits((int) ($session->amount_total ?? 0)),
            'payment_link' => $transaction->payment_link,
        ];
    }

    private function toMinorUnits(float $amount): int
    {
        return max((int) round($amount * 100), 0);
    }

    private function fromMinorUnits(int $amount): float
    {
        return round($amount / 100, 2);
    }

    /**
     * @param  array<string, string|int|float|bool|null>  $metadata
     * @return array<string, string>
     */
    private function buildMetadata(string $paymentKey, float $amount, float $paidAmount, array $metadata): array
    {
        $baseMetadata = [
            'payment_key' => $paymentKey,
            'total_amount' => (string) $amount,
            'paid_amount_before' => (string) $paidAmount,
        ];

        $customMetadata = collect($metadata)
            ->mapWithKeys(function (mixed $value, mixed $key): array {
                $metadataKey = $this->sanitizeString((string) $key, 40);
                $metadataValue = $this->sanitizeString((string) ($value ?? ''), 500);

                if ($metadataKey === null || $metadataValue === null) {
                    return [];
                }

                return [$metadataKey => $metadataValue];
            })
            ->all();

        return [...$baseMetadata, ...$customMetadata];
    }

    private function sanitizeEmail(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $email = trim(strtolower($value));

        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }

    private function sanitizeString(mixed $value, int $maxLength): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $clean = trim($value);
        if ($clean === '') {
            return null;
        }

        return Str::limit($clean, $maxLength, '');
    }

    private function assertHttpsUrl(string $url): string
    {
        $normalized = trim($url);

        if ($normalized === '' || ! str_starts_with($normalized, 'https://')) {
            throw new RuntimeException('Stripe redirect URLs must use HTTPS.');
        }

        return $normalized;
    }
}
