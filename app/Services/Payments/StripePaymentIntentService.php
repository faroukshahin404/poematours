<?php

namespace App\Services\Payments;

use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripePaymentIntentService
{
    /**
     * @param  array<string, mixed>  $metadata
     * @return array{id: string, client_secret: string}
     */
    public function create(
        string $secretKey,
        int $amount,
        string $currency,
        array $metadata = []
    ): array {
        Stripe::setApiKey($secretKey);

        $intent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => strtolower($currency),
            'metadata' => $metadata,
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        return [
            'id' => (string) $intent->id,
            'client_secret' => (string) $intent->client_secret,
        ];
    }
}
