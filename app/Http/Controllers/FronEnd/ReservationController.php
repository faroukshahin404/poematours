<?php

namespace App\Http\Controllers\FronEnd;

use App\Contracts\Repositories\Front\ReservationRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\FronEnd\StoreReservationRequest;
use App\Models\Language;
use App\Models\ReservationQuestion;
use App\Services\Frontend\ReservationNotificationService;
use App\Services\Payments\StripePaymentGatewayService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use RuntimeException;

class ReservationController extends Controller
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservationRepository,
        private readonly ReservationNotificationService $reservationNotificationService,
        private readonly StripePaymentGatewayService $stripePaymentGatewayService,
    ) {}

    public function create(): View
    {
        $locale = (string) session('locale', Language::defaultSlug());

        $dynamicQuestions = collect($this->reservationRepository->reservationPageQuestions())
            ->map(function (ReservationQuestion $question) use ($locale): array {
                $titleTranslations = $question->titleTranslations();
                $descriptionTranslations = $question->descriptionTranslations();

                return [
                    'id' => $question->id,
                    'title' => $titleTranslations[$locale] ?? reset($titleTranslations) ?: '',
                    'description' => $descriptionTranslations[$locale] ?? reset($descriptionTranslations) ?: '',
                    'type' => $question->type,
                    'options' => collect($question->normalizedOptions())
                        ->map(function (array $option) use ($locale): array {
                            $labelTranslations = $option['label'] ?? [];

                            return [
                                'label' => $labelTranslations[$locale] ?? reset($labelTranslations) ?: '',
                                'added_price' => (float) ($option['added_price'] ?? 0),
                            ];
                        })
                        ->values()
                        ->all(),
                ];
            })
            ->values()
            ->all();

        return view('frontend.reservation.index', [
            'dynamicQuestions' => $dynamicQuestions,
        ]);
    }

    public function store(StoreReservationRequest $request): RedirectResponse
    {
        $bookingSource = (string) ($request->input('booking_source', 'general'));
        $bookingPayload = $request->bookingPayload();

        if ($bookingSource === 'package') {
            $unitPrice = (float) ($bookingPayload['unit_price'] ?? 0);
            $travellersCount = max(1, (int) ($bookingPayload['travellers_count'] ?? 1));
            $computedTotal = round($unitPrice * $travellersCount, 2);

            $bookingPayload['total_amount'] = $computedTotal;
            $bookingPayload['paid_amount'] = 0.0;
            $bookingPayload['payment_status'] = 'not_paid';
        }

        $travellers = $request->travellersPayload();
        $files = [];
        foreach (array_keys($travellers) as $index) {
            $files[$index] = $request->file("travellers.{$index}.passport_photo");
        }

        $booking = $this->reservationRepository->createBooking(
            $bookingPayload,
            $travellers,
            $files,
        );

        $this->reservationNotificationService->notifyCreated($booking);

        if ($bookingSource === 'package') {
            $totalAmount = (float) ($booking->total_amount ?? 0);
            $depositAmount = round($totalAmount * 0.2, 2);

            if ($depositAmount <= 0) {
                return back()->withErrors([
                    'payment' => 'Unable to start payment because the deposit amount is invalid.',
                ]);
            }

            try {
                $payment = $this->stripePaymentGatewayService->createPaymentLink(
                    amount: $depositAmount,
                    clientInfo: [
                        'full_name' => trim((string) $booking->first_name.' '.$booking->last_name),
                        'email' => $booking->email,
                        'phone' => $booking->contact_phone_number,
                    ],
                    paidAmount: 0,
                    currency: 'USD',
                    metadata: [
                        'booking_id' => (string) $booking->id,
                        'booking_source' => 'package',
                        'package_id' => (string) ($booking->package_id ?? ''),
                    ],
                );
            } catch (RuntimeException $exception) {

                report($exception);

                return back()->withErrors([
                    'payment' => 'Reservation was saved, but payment session could not be created. Please contact support.',
                ]);
            }

            return redirect()->away($payment['payment_link']);
        }

        return redirect()
            ->route('reservation.create')
            ->with('status', 'Reservation submitted successfully. Our team will contact you shortly.');
    }
}
