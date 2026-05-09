<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\CountryRepositoryInterface;
use App\Models\Country;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class CountryRepository implements CountryRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Country::query()
            ->with(['creator:id,name', 'updater:id,name'])
            ->withCount('contacts')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?Country
    {
        return Country::query()->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data, int $userId): Country
    {
        $country = new Country;
        $country->name = $data['name'];
        $country->slug = Country::generateUniqueSlug($data['name']);
        $country->created_by = $userId;
        $country->save();

        return $country->fresh(['creator:id,name', 'updater:id,name']);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Country $country, array $data, int $userId): Country
    {
        $country->name = $data['name'];
        $country->slug = Country::generateUniqueSlug($data['name'], $country->id);
        $country->updated_by = $userId;
        $country->save();

        return $country->fresh(['creator:id,name', 'updater:id,name']);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Country $country): void
    {
        if ($country->contacts()->exists()) {
            throw ValidationException::withMessages([
                'country' => __('This country cannot be deleted because it is assigned to CRM contacts.'),
            ]);
        }

        $country->delete();
    }
}
