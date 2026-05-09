<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'title',
    'description',
    'type',
    'is_package_reservation',
    'is_reservation_page',
    'options',
    'created_by',
    'updated_by',
])]
class ReservationQuestion extends Model
{
    /**
     * Supported field types for reservation questions.
     *
     * @var array<int, string>
     */
    public const TYPES = ['select', 'multi_select', 'text', 'date'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'title' => 'array',
            'description' => 'array',
            'options' => 'array',
            'is_package_reservation' => 'boolean',
            'is_reservation_page' => 'boolean',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function titleTranslations(): array
    {
        return is_array($this->title) ? $this->title : [];
    }

    /**
     * @return array<string, string>
     */
    public function descriptionTranslations(): array
    {
        return is_array($this->description) ? $this->description : [];
    }

    /**
     * @return array<int, array{label: array<string, string>, added_price: float}>
     */
    public function normalizedOptions(): array
    {
        if (! in_array($this->type, ['select', 'multi_select'], true)) {
            return [];
        }

        $raw = is_array($this->options) ? $this->options : [];

        return collect($raw)
            ->filter(fn ($item): bool => is_array($item))
            ->map(function (array $item): array {
                $label = is_array($item['label'] ?? null) ? $item['label'] : [];

                return [
                    'label' => collect($label)
                        ->map(fn ($value): string => trim((string) $value))
                        ->filter(fn (string $value): bool => $value !== '')
                        ->all(),
                    'added_price' => round((float) ($item['added_price'] ?? 0), 2),
                ];
            })
            ->values()
            ->all();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
