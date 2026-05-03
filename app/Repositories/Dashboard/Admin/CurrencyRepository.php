<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\CurrencyRepositoryInterface;
use App\Models\Currency;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Currency::query()
            ->with(['creator:id,name', 'updater:id,name'])
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?Currency
    {
        return Currency::query()->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data, int $userId): Currency
    {
        return DB::transaction(function () use ($data, $userId) {
            $isFirst = Currency::query()->count() === 0;
            $isDefault = $isFirst || (bool) ($data['is_default'] ?? false);

            if ($isDefault) {
                Currency::query()->update(['is_default' => false]);
            }

            $currency = new Currency;
            $currency->name = $data['name'];
            $currency->slug = Currency::generateUniqueSlug($data['name']);
            $currency->is_default = $isDefault;
            $currency->created_by = $userId;
            $currency->save();

            return $currency->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function update(Currency $currency, array $data, int $userId): Currency
    {
        return DB::transaction(function () use ($currency, $data, $userId) {
            $onlyOne = Currency::query()->count() === 1;
            $wasDefault = $currency->is_default;
            $isDefault = $onlyOne ? true : (bool) ($data['is_default'] ?? false);

            if ($isDefault) {
                Currency::query()->where('id', '!=', $currency->id)->update(['is_default' => false]);
            } elseif ($wasDefault) {
                $replacement = Currency::query()
                    ->where('id', '!=', $currency->id)
                    ->orderBy('id')
                    ->first();

                if ($replacement !== null) {
                    $replacement->forceFill([
                        'is_default' => true,
                        'updated_by' => $userId,
                    ])->save();
                } else {
                    $isDefault = true;
                }
            }

            $currency->name = $data['name'];
            $currency->slug = Currency::generateUniqueSlug($data['name'], $currency->id);
            $currency->is_default = $isDefault;
            $currency->updated_by = $userId;
            $currency->save();

            return $currency->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Currency $currency, int $userId): void
    {
        DB::transaction(function () use ($currency, $userId) {
            $wasDefault = $currency->is_default;
            $currency->delete();

            if ($wasDefault) {
                $next = Currency::query()->orderBy('id')->first();
                if ($next !== null) {
                    $next->forceFill([
                        'is_default' => true,
                        'updated_by' => $userId,
                    ])->save();
                }
            }
        });
    }
}
