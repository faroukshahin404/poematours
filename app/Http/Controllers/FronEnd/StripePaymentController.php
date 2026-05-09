<?php

namespace App\Http\Controllers\FronEnd;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Admin\PaymentSettingsService;
use App\Services\Payments\StripeWebhookService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripePaymentController extends Controller
{
    public function __construct(
        private readonly PaymentSettingsService $paymentSettingsService,
        private readonly StripeWebhookService $stripeWebhookService,
    ) {}

    public function success(Request $request): View
    {
        return view('frontend.payment.success', [
            'sessionId' => (string) $request->query('session_id', ''),
        ]);
    }

    public function failure(): View
    {
        return view('frontend.payment.failure');
    }

    public function webhook(Request $request): JsonResponse
    {
        $settings = $this->paymentSettingsService->stripeSettingsForRuntime();
        $webhookSecret = (string) ($settings['webhook_secret'] ?? '');

        if (trim($webhookSecret) === '') {
            return response()->json([
                'message' => 'Stripe webhook secret is not configured.',
            ], 503);
        }

        $payload = $request->getContent();
        $signature = (string) $request->header('Stripe-Signature', '');

        try {
            /** @var Event $event */
            $event = Webhook::constructEvent($payload, $signature, $webhookSecret);
        } catch (UnexpectedValueException|SignatureVerificationException) {
            return response()->json([
                'message' => 'Invalid Stripe webhook signature.',
            ], 400);
        }

        $this->stripeWebhookService->handle($event);

        return response()->json(['received' => true]);
    }
}
