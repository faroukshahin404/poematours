<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\CrmServiceRepositoryInterface;
use App\Models\CrmService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class CrmServiceRepository implements CrmServiceRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return CrmService::query()
            ->with(['creator:id,name', 'updater:id,name'])
            ->withCount('contacts')
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function create(array $data, int $userId): CrmService
    {
        $service = new CrmService;
        $service->name = $data['name'];
        $service->created_by = $userId;
        $service->save();

        return $service->fresh(['creator:id,name', 'updater:id,name']);
    }

    public function update(CrmService $service, array $data, int $userId): CrmService
    {
        $service->name = $data['name'];
        $service->updated_by = $userId;
        $service->save();

        return $service->fresh(['creator:id,name', 'updater:id,name']);
    }

    public function delete(CrmService $service): void
    {
        if ($service->contacts()->exists()) {
            throw ValidationException::withMessages([
                'service' => __('This service cannot be deleted because it is assigned to contacts.'),
            ]);
        }

        $service->delete();
    }
}
