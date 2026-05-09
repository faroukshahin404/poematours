<?php

namespace App\Http\Requests\Dashboard\Admin\Concerns;

use App\Models\Language;
use App\Models\ReservationQuestion;
use Illuminate\Validation\Validator;

trait BuildsReservationQuestionPayload
{
    /**
     * @return array<int, string>
     */
    protected function languageSlugs(): array
    {
        return Language::query()->pluck('slug')->map(fn ($slug): string => (string) $slug)->all();
    }

    protected function defaultLanguageSlug(): string
    {
        return Language::defaultSlug();
    }

    protected function ensureValidLanguageKeys(Validator $validator): void
    {
        $allowed = $this->languageSlugs();
        $fields = ['title', 'description'];

        foreach ($fields as $field) {
            foreach (array_keys($this->input($field, [])) as $key) {
                if (! in_array((string) $key, $allowed, true)) {
                    $validator->errors()->add($field, __('Invalid language key in :field.', ['field' => $field]));

                    return;
                }
            }
        }

        foreach ($this->input('options', []) as $index => $option) {
            if (! is_array($option)) {
                continue;
            }

            foreach (array_keys($option['label'] ?? []) as $key) {
                if (! in_array((string) $key, $allowed, true)) {
                    $validator->errors()->add("options.{$index}.label", __('Invalid language key in option labels.'));

                    return;
                }
            }
        }
    }

    /**
     * @return array{
     *     title: array<string, string>,
     *     description: array<string, string>,
     *     type: string,
     *     is_package_reservation: bool,
     *     is_reservation_page: bool,
     *     options: array<int, array{label: array<string, string>, added_price: float}>
     * }
     */
    public function questionPayload(): array
    {
        $validated = $this->validated();
        $allowed = array_flip($this->languageSlugs());

        $title = array_map(
            fn (mixed $value): string => trim((string) $value),
            array_intersect_key($validated['title'] ?? [], $allowed),
        );

        $description = array_map(
            fn (mixed $value): string => trim((string) $value),
            array_intersect_key($validated['description'] ?? [], $allowed),
        );
        $description = array_filter($description, fn (string $value): bool => $value !== '');

        $type = (string) ($validated['type'] ?? 'text');
        $options = [];

        if (in_array($type, ['select', 'multi_select'], true)) {
            $options = collect($validated['options'] ?? [])
                ->filter(fn ($option): bool => is_array($option))
                ->map(function (array $option) use ($allowed): array {
                    $labels = array_map(
                        fn (mixed $value): string => trim((string) $value),
                        array_intersect_key($option['label'] ?? [], $allowed),
                    );
                    $labels = array_filter($labels, fn (string $value): bool => $value !== '');

                    return [
                        'label' => $labels,
                        'added_price' => round((float) ($option['added_price'] ?? 0), 2),
                    ];
                })
                ->values()
                ->all();
        }

        return [
            'title' => $title,
            'description' => $description,
            'type' => in_array($type, ReservationQuestion::TYPES, true) ? $type : 'text',
            'is_package_reservation' => (bool) ($validated['is_package_reservation'] ?? false),
            'is_reservation_page' => (bool) ($validated['is_reservation_page'] ?? false),
            'options' => $options,
        ];
    }
}
