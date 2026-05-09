<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\ReservationQuestionRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StoreReservationQuestionRequest;
use App\Http\Requests\Dashboard\Admin\UpdateReservationQuestionRequest;
use App\Models\Language;
use App\Models\ReservationQuestion;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ReservationQuestionController extends Controller
{
    public function __construct(
        private readonly ReservationQuestionRepositoryInterface $questions
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/ReservationQuestions/Index', [
            'reservationQuestions' => $this->questions->paginate(15)->through(function (ReservationQuestion $question): array {
                $default = Language::defaultSlug();
                $title = $question->titleTranslations();

                return [
                    'id' => $question->id,
                    'title' => $title[$default] ?? reset($title) ?: '',
                    'type' => $question->type,
                    'is_package_reservation' => (bool) $question->is_package_reservation,
                    'is_reservation_page' => (bool) $question->is_reservation_page,
                    'options_count' => count($question->normalizedOptions()),
                    'creator' => $question->creator?->name,
                    'updater' => $question->updater?->name,
                ];
            }),
            'types' => ReservationQuestion::TYPES,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/ReservationQuestions/Create', [
            'types' => ReservationQuestion::TYPES,
        ]);
    }

    public function store(StoreReservationQuestionRequest $request): RedirectResponse
    {
        $this->questions->create(
            $request->questionPayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.reservation-questions.index')
            ->with('status', __('Reservation question created successfully.'));
    }

    public function edit(ReservationQuestion $reservationQuestion): Response
    {
        return Inertia::render('Dashboard/Admin/ReservationQuestions/Edit', [
            'reservationQuestion' => [
                'id' => $reservationQuestion->id,
                'title' => $reservationQuestion->titleTranslations(),
                'description' => $reservationQuestion->descriptionTranslations(),
                'type' => $reservationQuestion->type,
                'is_package_reservation' => (bool) $reservationQuestion->is_package_reservation,
                'is_reservation_page' => (bool) $reservationQuestion->is_reservation_page,
                'options' => $reservationQuestion->normalizedOptions(),
            ],
            'types' => ReservationQuestion::TYPES,
        ]);
    }

    public function update(
        UpdateReservationQuestionRequest $request,
        ReservationQuestion $reservationQuestion
    ): RedirectResponse {
        $this->questions->update(
            $reservationQuestion,
            $request->questionPayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.reservation-questions.index')
            ->with('status', __('Reservation question updated successfully.'));
    }

    public function destroy(ReservationQuestion $reservationQuestion): RedirectResponse
    {
        $this->questions->delete($reservationQuestion);

        return redirect()
            ->route('admin.reservation-questions.index')
            ->with('status', __('Reservation question deleted successfully.'));
    }
}
