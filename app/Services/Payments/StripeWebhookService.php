<?php

namespace App\Services\Payments;

use App\Models\PaymentTransaction;
use Stripe\Event;
use Stripe\StripeObject;

class StripeWebhookService
{
    public function handle(Event $event): void
    {
        if (! in_array($event->type, [
            'checkout.session.completed',
            'checkout.session.async_payment_succeeded',
            'checkout.session.async_payment_failed',
            'checkout.session.expired',
        ], true)) {
            return;
        }

        /** @var StripeObject|null $object */
        $object = $event->data->object;
        if (! $object instanceof StripeObject) {
            return;
        }

        $sessionId = (string) ($object['id'] ?? '');
        if ($sessionId === '') {
            return;
        }

        $transaction = PaymentTransaction::query()
            ->where('stripe_session_id', $sessionId)
            ->first();

        if (! $transaction instanceof PaymentTransaction) {
            return;
        }

        $existingGatewayPayload = is_array($transaction->gateway_payload) ? $transaction->gateway_payload : [];

        $transaction->update([
            'status' => (string) ($object['payment_status'] ?? $transaction->status),
            'gateway_payload' => [
                ...$existingGatewayPayload,
                'event_type' => $event->type,
                'session_status' => (string) ($object['status'] ?? ''),
                'payment_status' => (string) ($object['payment_status'] ?? ''),
                'metadata' => is_array($object['metadata'] ?? null) ? $object['metadata'] : data_get($existingGatewayPayload, 'metadata', []),
            ],
        ]);
    }
}
