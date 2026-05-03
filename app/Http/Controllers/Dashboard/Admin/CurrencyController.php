<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\CurrencyRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StoreCurrencyRequest;
use App\Http\Requests\Dashboard\Admin\UpdateCurrencyRequest;
use App\Models\Currency;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CurrencyController extends Controller
{
    public function __construct(
        private readonly CurrencyRepositoryInterface $currencies
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/Currencies/Index', [
            'currencies' => $this->currencies->paginate(15),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/Currencies/Create', [
            'currencyCount' => Currency::query()->count(),
        ]);
    }

    public function store(StoreCurrencyRequest $request): RedirectResponse
    {
        $this->currencies->create(
            $request->currencyPayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.currencies.index')
            ->with('status', __('Currency created successfully.'));
    }

    public function edit(Currency $currency): Response
    {
        return Inertia::render('Dashboard/Admin/Currencies/Edit', [
            'currency' => [
                'id' => $currency->id,
                'name' => $currency->name,
                'slug' => $currency->slug,
                'is_default' => $currency->is_default,
            ],
            'currencyCount' => Currency::query()->count(),
        ]);
    }

    public function update(UpdateCurrencyRequest $request, Currency $currency): RedirectResponse
    {
        $this->currencies->update(
            $currency,
            $request->currencyPayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.currencies.index')
            ->with('status', __('Currency updated successfully.'));
    }

    public function destroy(Request $request, Currency $currency): RedirectResponse
    {
        $this->currencies->delete($currency, (int) $request->user()->id);

        return redirect()
            ->route('admin.currencies.index')
            ->with('status', __('Currency deleted successfully.'));
    }
}
