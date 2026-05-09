<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\CountryRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StoreCountryRequest;
use App\Http\Requests\Dashboard\Admin\UpdateCountryRequest;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CountryController extends Controller
{
    public function __construct(
        private readonly CountryRepositoryInterface $countries
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/Countries/Index', [
            'countries' => $this->countries->paginate(15),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/Countries/Create');
    }

    public function store(StoreCountryRequest $request): RedirectResponse
    {
        $this->countries->create(
            $request->countryPayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.countries.index')
            ->with('status', __('Country created successfully.'));
    }

    public function edit(Country $country): Response
    {
        return Inertia::render('Dashboard/Admin/Countries/Edit', [
            'country' => [
                'id' => $country->id,
                'name' => $country->name,
                'slug' => $country->slug,
            ],
        ]);
    }

    public function update(UpdateCountryRequest $request, Country $country): RedirectResponse
    {
        $this->countries->update(
            $country,
            $request->countryPayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.countries.index')
            ->with('status', __('Country updated successfully.'));
    }

    public function destroy(Request $request, Country $country): RedirectResponse
    {
        $this->countries->delete($country);

        return redirect()
            ->route('admin.countries.index')
            ->with('status', __('Country deleted successfully.'));
    }
}
