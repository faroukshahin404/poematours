<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\CrmContact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CrmContactRepositoryInterface
{
    /**
     * @param array{
     *     search?: string|null,
     *     status?: string|null,
     *     country_id?: int|null,
     *     service_id?: int|null,
     *     created_by?: int|null,
     *     updated_by?: int|null,
     *     archived?: string|null,
     *     source?: string|null,
     *     created_from?: string|null,
     *     created_to?: string|null
     * } $filters
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?CrmContact;

    /**
     * @return Collection<int, array{id: int, name: string}>
     */
    public function countryOptions(): Collection;

    /**
     * @return Collection<int, array{id: int, name: string}>
     */
    public function serviceOptions(): Collection;

    /**
     * @return Collection<int, array{id: int, name: string}>
     */
    public function userOptions(): Collection;

    /**
     * @param  array{name: string, phone: string, email?: string|null, country_id: int, status: string, source?: string, notes?: string|null, service_ids?: array<int, int>}  $data
     */
    public function create(array $data, int $userId): CrmContact;

    /**
     * @param  array{name: string, phone: string, email?: string|null, country_id: int, status: string, source?: string, notes?: string|null, service_ids?: array<int, int>}  $data
     */
    public function update(CrmContact $contact, array $data, int $userId): CrmContact;

    public function updateStatus(CrmContact $contact, string $status, int $userId): CrmContact;

    public function updateArchiveState(CrmContact $contact, bool $archived, int $userId): CrmContact;

    /**
     * @param array{
     *     search?: string|null,
     *     status?: string|null,
     *     country_id?: int|null,
     *     service_id?: int|null,
     *     created_by?: int|null,
     *     updated_by?: int|null,
     *     archived?: string|null,
     *     source?: string|null,
     *     created_from?: string|null,
     *     created_to?: string|null
     * } $filters
     * @return Collection<int, array<string, mixed>>
     */
    public function exportRows(array $filters = []): Collection;

    public function delete(CrmContact $contact): void;
}
