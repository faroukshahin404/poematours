<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\LanguageRepositoryInterface;
use App\Models\Language;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LanguageRepository implements LanguageRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Language::query()
            ->with(['creator:id,name', 'updater:id,name'])
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?Language
    {
        return Language::query()->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data, int $userId): Language
    {
        return DB::transaction(function () use ($data, $userId) {
            $isFirst = Language::query()->count() === 0;
            $isDefault = $isFirst || (bool) ($data['is_default'] ?? false);

            if ($isDefault) {
                Language::query()->update(['is_default' => false]);
            }

            $language = new Language;
            $language->name = $data['name'];
            $language->slug = Language::generateUniqueSlug($data['name']);
            $language->is_default = $isDefault;
            $language->created_by = $userId;
            $language->save();

            return $language->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function update(Language $language, array $data, int $userId): Language
    {
        return DB::transaction(function () use ($language, $data, $userId) {
            $onlyOne = Language::query()->count() === 1;
            $wasDefault = $language->is_default;
            $isDefault = $onlyOne ? true : (bool) ($data['is_default'] ?? false);

            if ($isDefault) {
                Language::query()->where('id', '!=', $language->id)->update(['is_default' => false]);
            } elseif ($wasDefault) {
                $replacement = Language::query()
                    ->where('id', '!=', $language->id)
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

            $language->name = $data['name'];
            $language->slug = Language::generateUniqueSlug($data['name'], $language->id);
            $language->is_default = $isDefault;
            $language->updated_by = $userId;
            $language->save();

            return $language->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Language $language, int $userId): void
    {
        DB::transaction(function () use ($language, $userId) {
            $wasDefault = $language->is_default;
            $language->delete();

            if ($wasDefault) {
                $next = Language::query()->orderBy('id')->first();
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
