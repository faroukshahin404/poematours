<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\CrmContactRepositoryInterface;
use App\Models\Country;
use App\Models\CrmContact;
use App\Models\CrmService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CrmContactRepository implements CrmContactRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return CrmContact::query()
            ->with(['country:id,name', 'services:id,name', 'creator:id,name', 'updater:id,name', 'archiver:id,name'])
            ->when(! empty($filters['search']), function ($query) use ($filters) {
                $search = trim((string) $filters['search']);
                $query->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(! empty($filters['status']), fn ($query) => $query->where('status', $filters['status']))
            ->when(! empty($filters['country_id']), fn ($query) => $query->where('country_id', (int) $filters['country_id']))
            ->when(! empty($filters['created_by']), fn ($query) => $query->where('created_by', (int) $filters['created_by']))
            ->when(! empty($filters['updated_by']), fn ($query) => $query->where('updated_by', (int) $filters['updated_by']))
            ->when(! empty($filters['source']), fn ($query) => $query->where('source', (string) $filters['source']))
            ->when(! empty($filters['service_id']), function ($query) use ($filters) {
                $serviceId = (int) $filters['service_id'];
                $query->whereHas('services', fn ($nested) => $nested->where('crm_services.id', $serviceId));
            })
            ->when(! empty($filters['created_from']), function ($query) use ($filters) {
                $from = Carbon::parse($filters['created_from'])->startOfDay();
                $query->where('created_at', '>=', $from);
            })
            ->when(! empty($filters['created_to']), function ($query) use ($filters) {
                $to = Carbon::parse($filters['created_to'])->endOfDay();
                $query->where('created_at', '<=', $to);
            })
            ->when(($filters['archived'] ?? null) === 'archived', fn ($query) => $query->whereNotNull('archived_at'))
            ->when(($filters['archived'] ?? null) !== 'archived', fn ($query) => $query->whereNull('archived_at'))
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?CrmContact
    {
        return CrmContact::query()->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function countryOptions(): Collection
    {
        return Country::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * {@inheritdoc}
     */
    public function serviceOptions(): Collection
    {
        return CrmService::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * {@inheritdoc}
     */
    public function userOptions(): Collection
    {
        return User::query()
            ->where('is_admin', true)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data, int $userId): CrmContact
    {
        return DB::transaction(function () use ($data, $userId) {
            $contact = new CrmContact;
            $contact->name = $data['name'];
            $contact->phone = $data['phone'];
            $contact->email = $data['email'] ?? null;
            $contact->country_id = $data['country_id'];
            $contact->status = $data['status'];
            $contact->source = $data['source'] ?? CrmContact::SOURCE_MANUAL;
            $contact->notes = $data['notes'] ?? null;
            $contact->created_by = $userId;
            $contact->save();
            $contact->services()->sync($data['service_ids'] ?? []);

            return $contact->fresh(['country:id,name', 'services:id,name', 'creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function update(CrmContact $contact, array $data, int $userId): CrmContact
    {
        return DB::transaction(function () use ($contact, $data, $userId) {
            $contact->name = $data['name'];
            $contact->phone = $data['phone'];
            $contact->email = $data['email'] ?? null;
            $contact->country_id = $data['country_id'];
            $contact->status = $data['status'];
            $contact->source = $data['source'] ?? $contact->source;
            $contact->notes = $data['notes'] ?? null;
            $contact->updated_by = $userId;
            $contact->save();
            $contact->services()->sync($data['service_ids'] ?? []);

            return $contact->fresh(['country:id,name', 'services:id,name', 'creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function updateStatus(CrmContact $contact, string $status, int $userId): CrmContact
    {
        $contact->status = $status;
        $contact->updated_by = $userId;
        $contact->save();

        return $contact->fresh(['country:id,name', 'services:id,name', 'creator:id,name', 'updater:id,name']);
    }

    /**
     * {@inheritdoc}
     */
    public function updateArchiveState(CrmContact $contact, bool $archived, int $userId): CrmContact
    {
        $contact->archived_at = $archived ? now() : null;
        $contact->archived_by = $archived ? $userId : null;
        $contact->updated_by = $userId;
        $contact->save();

        return $contact->fresh(['country:id,name', 'services:id,name', 'creator:id,name', 'updater:id,name', 'archiver:id,name']);
    }

    /**
     * {@inheritdoc}
     */
    public function exportRows(array $filters = []): Collection
    {
        return CrmContact::query()
            ->with(['country:id,name', 'services:id,name', 'creator:id,name', 'updater:id,name', 'archiver:id,name'])
            ->when(! empty($filters['search']), function ($query) use ($filters) {
                $search = trim((string) $filters['search']);
                $query->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(! empty($filters['status']), fn ($query) => $query->where('status', $filters['status']))
            ->when(! empty($filters['country_id']), fn ($query) => $query->where('country_id', (int) $filters['country_id']))
            ->when(! empty($filters['created_by']), fn ($query) => $query->where('created_by', (int) $filters['created_by']))
            ->when(! empty($filters['updated_by']), fn ($query) => $query->where('updated_by', (int) $filters['updated_by']))
            ->when(! empty($filters['source']), fn ($query) => $query->where('source', (string) $filters['source']))
            ->when(! empty($filters['service_id']), function ($query) use ($filters) {
                $serviceId = (int) $filters['service_id'];
                $query->whereHas('services', fn ($nested) => $nested->where('crm_services.id', $serviceId));
            })
            ->when(! empty($filters['created_from']), function ($query) use ($filters) {
                $from = Carbon::parse($filters['created_from'])->startOfDay();
                $query->where('created_at', '>=', $from);
            })
            ->when(! empty($filters['created_to']), function ($query) use ($filters) {
                $to = Carbon::parse($filters['created_to'])->endOfDay();
                $query->where('created_at', '<=', $to);
            })
            ->when(($filters['archived'] ?? null) === 'archived', fn ($query) => $query->whereNotNull('archived_at'))
            ->when(($filters['archived'] ?? null) !== 'archived', fn ($query) => $query->whereNull('archived_at'))
            ->orderByDesc('id')
            ->get()
            ->map(function (CrmContact $contact) {
                return [
                    'name' => $contact->name,
                    'phone' => $contact->phone,
                    'email' => $contact->email,
                    'country' => $contact->country?->name,
                    'status' => CrmContact::statusLabels()[$contact->status] ?? $contact->status,
                    'source' => CrmContact::sourceLabels()[$contact->source] ?? $contact->source,
                    'services' => $contact->services->pluck('name')->implode(', '),
                    'notes' => $contact->notes,
                    'created_by' => $contact->creator?->name,
                    'updated_by' => $contact->updater?->name,
                    'archived' => $contact->archived_at !== null ? 'Yes' : 'No',
                    'archived_by' => $contact->archiver?->name,
                    'created_at' => optional($contact->created_at)?->toDateTimeString(),
                    'updated_at' => optional($contact->updated_at)?->toDateTimeString(),
                ];
            });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(CrmContact $contact): void
    {
        $contact->delete();
    }
}
