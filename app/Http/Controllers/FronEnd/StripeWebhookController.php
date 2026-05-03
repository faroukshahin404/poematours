<?php

namespace App\Http\Controllers\FronEnd;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\Settings\SettingService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function __construct(
        private readonly SettingService $settingService
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $paymentSettings = $this->settingService->paymentSettings();
        $secret = (string) ($paymentSettings['webhook_secret'] ?? '');

        if ($secret === '') {
            return response('Webhook secret is not configured.', 400);
        }

        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                (string) $request->header('Stripe-Signature', ''),
                $secret
            );
        } catch (UnexpectedValueException|SignatureVerificationException) {
            return response('Invalid webhook signature.', 400);
        }

        $intentId = (string) data_get($event, 'data.object.id', '');
        if ($intentId === '') {
            return response('Payment intent id is missing.', 400);
        }

        $reservation = Reservation::query()->where('stripe_payment_intent_id', $intentId)->first();
        if (! $reservation) {
            return response('Reservation not found.', 404);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $reservation->update([
                'payment_status' => 'paid',
                'status' => 'deposit_paid',
                'paid_at' => now(),
            ]);
        }

        if ($event->type === 'payment_intent.payment_failed') {
            $reservation->update([
                'payment_status' => 'failed',
            ]);
        }

        if ($event->type === 'payment_intent.canceled') {
            $reservation->update([
                'payment_status' => 'canceled',
                'status' => 'cancelled',
            ]);
        }

        return response('ok');
    }
}
