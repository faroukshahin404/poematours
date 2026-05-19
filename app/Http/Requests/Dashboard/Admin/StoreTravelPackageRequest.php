<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\Language;
use App\Models\PackageLabel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreTravelPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $default = Language::defaultSlug();
        $titles = $this->input('title', []);
        if (! $this->filled('slug')) {
            $this->merge([
                'slug' => Str::slug((string) ($titles[$default] ?? 'package')),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $default = Language::defaultSlug();

        return [
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('packages', 'slug')],
            'title' => ['required', 'array'],
            'title.'.$default => ['required', 'string', 'max:500'],
            'description' => ['required', 'array'],
            'description.'.$default => ['required', 'string'],
            'details' => ['nullable', 'array'],
            'details.overview_title' => ['nullable', 'array'],
            'details.overview_title.'.$default => ['nullable', 'string', 'max:500'],
            'details.overview_intro' => ['nullable', 'array'],
            'details.overview_intro.'.$default => ['nullable', 'string'],
            'details.overview_lead' => ['nullable', 'array'],
            'details.overview_lead.'.$default => ['nullable', 'string'],
            'details.overview_support' => ['nullable', 'array'],
            'details.overview_support.'.$default => ['nullable', 'string'],
            'details.ship_name' => ['nullable', 'array'],
            'details.ship_name.'.$default => ['nullable', 'string', 'max:500'],
            'details.ship_description' => ['nullable', 'array'],
            'details.ship_description.'.$default => ['nullable', 'string'],
            'details.map_image' => ['nullable', 'string', 'max:255'],
            'details.overview_highlights' => ['nullable', 'array'],
            'details.overview_highlights.*' => ['nullable', 'string'],
            'details.essential_info' => ['nullable', 'array'],
            'details.essential_info.*.question' => ['required_with:details.essential_info', 'string'],
            'details.essential_info.*.answer' => ['required_with:details.essential_info', 'string'],
            'details.offer_cards' => ['nullable', 'array'],
            'details.offer_cards.*.title' => ['required_with:details.offer_cards', 'string'],
            'details.offer_cards.*.description' => ['required_with:details.offer_cards', 'string'],
            'details.offer_cards.*.link_label' => ['nullable', 'string', 'max:255'],
            'details.offer_cards.*.link_url' => ['nullable', 'string', 'max:500'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120'],
            'package_category_ids' => ['nullable', 'array'],
            'package_category_ids.*' => ['integer', 'exists:package_categories,id'],
            'package_label_group_ids' => ['nullable', 'array'],
            'package_label_group_ids.*' => ['integer', 'exists:package_label_groups,id'],
            'package_label_ids' => ['nullable', 'array'],
            'package_label_ids.*' => ['integer', 'exists:package_labels,id'],
            'activity_ids' => ['nullable', 'array'],
            'activity_ids.*' => ['integer', 'exists:activities,id'],
            'package_inclusion_ids' => ['nullable', 'array'],
            'package_inclusion_ids.*' => ['integer', 'exists:package_inclusions,id'],
            'extensions' => ['nullable', 'array'],
            'extensions.*.extension_package_id' => ['required_with:extensions', 'integer', 'exists:packages,id'],
            'extensions.*.type' => ['nullable', 'string', Rule::in(['pre_tour', 'post_tour'])],
            'extensions.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'extensions.*.inclusions_text' => ['nullable', 'string', 'max:1000'],
            'itineraries' => ['nullable', 'array'],
            'itineraries.*.title' => ['required_with:itineraries', 'string', 'max:255'],
            'itineraries.*.description' => ['nullable', 'string'],
            'itineraries.*.meals_included' => ['nullable', 'array'],
            'itineraries.*.destination_id' => ['required_with:itineraries', 'integer', 'exists:destinations,id'],
            'itineraries.*.hotel_id' => ['nullable', 'integer', 'exists:hotels,id'],
            'itineraries.*.boat_id' => ['nullable', 'integer', 'exists:boats,id'],
            'date_prices' => ['nullable', 'array'],
            'date_prices.*.from_date' => ['required_with:date_prices', 'date'],
            'date_prices.*.to_date' => ['required_with:date_prices', 'date', 'after_or_equal:date_prices.*.from_date'],
            'date_prices.*.available_seats' => ['required_with:date_prices', 'integer', 'min:0'],
            'date_prices.*.price' => ['required_with:date_prices', 'numeric', 'min:0'],
            'date_prices.*.offer' => ['nullable', 'numeric', 'min:0'],
            'date_prices.*.accommodations' => ['nullable', 'array'],
            'date_prices.*.accommodations.*.hotel_id' => ['required_with:date_prices.*.accommodations', 'integer', 'exists:hotels,id'],
            'date_prices.*.accommodations.*.room_id' => ['required_with:date_prices.*.accommodations', 'integer', 'exists:hotel_rooms,id'],
            'featured' => ['nullable', 'boolean'],
            'recommended' => ['nullable', 'boolean'],
            'is_private' => ['nullable', 'boolean'],
            'is_small_group' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function packagePayload(): array
    {
        $validated = $this->validated();
        $allowed = Language::query()->pluck('slug')->map(fn ($s) => (string) $s)->flip()->all();

        $packageLabelIds = array_map('intval', $validated['package_label_ids'] ?? []);
        $packageLabelIds = $this->syncOptionLabelIds(
            $packageLabelIds,
            (bool) ($validated['featured'] ?? false),
            (bool) ($validated['recommended'] ?? false),
        );

        return [
            'slug' => (string) ($validated['slug'] ?? ''),
            'title' => array_map('trim', array_intersect_key($validated['title'], $allowed)),
            'description' => array_map('trim', array_intersect_key($validated['description'], $allowed)),
            'details' => $this->normalizeDetails($validated['details'] ?? [], $allowed),
            'package_category_ids' => array_map('intval', $validated['package_category_ids'] ?? []),
            'package_label_group_ids' => array_map('intval', $validated['package_label_group_ids'] ?? []),
            'package_label_ids' => $packageLabelIds,
            'activity_ids' => array_map('intval', $validated['activity_ids'] ?? []),
            'package_inclusion_ids' => array_map('intval', $validated['package_inclusion_ids'] ?? []),
            'extensions' => $this->normalizeExtensions($validated['extensions'] ?? []),
            'itineraries' => $this->normalizeItineraries($validated['itineraries'] ?? []),
            'date_prices' => $this->normalizeDatePrices($validated['date_prices'] ?? []),
            'remove_media_ids' => [],
            'remove_pdf' => false,
            'featured' => (int) ($validated['featured'] ?? 0),
            'recommended' => (int) ($validated['recommended'] ?? 0),
            'is_private' => (int) ($validated['is_private'] ?? 0),
            'is_small_group' => (int) ($validated['is_small_group'] ?? 0),
        ];
    }

    /**
     * @param  array<string, mixed>  $details
     * @param  array<string, int>  $allowed
     * @return array<string, mixed>
     */
    private function normalizeDetails(array $details, array $allowed): array
    {
        $localized = static fn (mixed $value) => is_array($value)
            ? array_map('trim', array_intersect_key($value, $allowed))
            : [];

        return [
            'overview_title' => $localized($details['overview_title'] ?? []),
            'overview_intro' => $localized($details['overview_intro'] ?? []),
            'overview_lead' => $localized($details['overview_lead'] ?? []),
            'overview_support' => $localized($details['overview_support'] ?? []),
            'ship_name' => $localized($details['ship_name'] ?? []),
            'ship_description' => $localized($details['ship_description'] ?? []),
            'map_image' => trim((string) ($details['map_image'] ?? '')),
            'overview_highlights' => collect($details['overview_highlights'] ?? [])
                ->map(fn (mixed $item): string => trim((string) $item))
                ->filter(fn (string $item): bool => $item !== '')
                ->values()
                ->all(),
            'essential_info' => collect($details['essential_info'] ?? [])
                ->map(fn (mixed $item): array => [
                    'question' => trim((string) (($item['question'] ?? ''))),
                    'answer' => trim((string) (($item['answer'] ?? ''))),
                ])
                ->filter(fn (array $item): bool => $item['question'] !== '' && $item['answer'] !== '')
                ->values()
                ->all(),
            'offer_cards' => collect($details['offer_cards'] ?? [])
                ->map(fn (mixed $item): array => [
                    'title' => trim((string) (($item['title'] ?? ''))),
                    'description' => trim((string) (($item['description'] ?? ''))),
                    'link_label' => trim((string) (($item['link_label'] ?? 'Learn more'))),
                    'link_url' => trim((string) (($item['link_url'] ?? ''))),
                ])
                ->filter(fn (array $item): bool => $item['title'] !== '' && $item['description'] !== '')
                ->values()
                ->all(),
        ];
    }

    private function normalizeExtensions(array $rows): array
    {
        return collect($rows)
            ->map(function (array $row, int $index): array {
                return [
                    'extension_package_id' => (int) ($row['extension_package_id'] ?? 0),
                    'type' => in_array($row['type'] ?? '', ['pre_tour', 'post_tour'], true) ? $row['type'] : 'pre_tour',
                    'sort_order' => isset($row['sort_order']) ? (int) $row['sort_order'] : $index,
                    'inclusions_text' => isset($row['inclusions_text']) ? trim((string) $row['inclusions_text']) : null,
                ];
            })
            ->filter(fn (array $row): bool => $row['extension_package_id'] > 0)
            ->unique('extension_package_id')
            ->values()
            ->all();
    }

    private function normalizeItineraries(array $rows): array
    {
        return array_map(function (array $row): array {
            $meals = $row['meals_included'] ?? [];

            return [
                'title' => trim((string) ($row['title'] ?? '')),
                'description' => isset($row['description']) ? trim((string) $row['description']) : null,
                'meals_included' => [
                    'breakfast' => (bool) ($meals['breakfast'] ?? false),
                    'lunch' => (bool) ($meals['lunch'] ?? false),
                    'dinner' => (bool) ($meals['dinner'] ?? false),
                    'snacks' => (bool) ($meals['snacks'] ?? false),
                ],
                'destination_id' => (int) $row['destination_id'],
                'hotel_id' => isset($row['hotel_id']) ? (int) $row['hotel_id'] : null,
                'boat_id' => isset($row['boat_id']) ? (int) $row['boat_id'] : null,
            ];
        }, $rows);
    }

    private function normalizeDatePrices(array $rows): array
    {
        return array_map(function (array $row): array {
            $accommodations = array_map(function (array $acc): array {
                return [
                    'hotel_id' => (int) $acc['hotel_id'],
                    'room_id' => (int) $acc['room_id'],
                ];
            }, $row['accommodations'] ?? []);

            return [
                'from_date' => (string) $row['from_date'],
                'to_date' => (string) $row['to_date'],
                'available_seats' => (int) $row['available_seats'],
                'price' => (float) $row['price'],
                'offer' => isset($row['offer']) && $row['offer'] !== '' ? (float) $row['offer'] : null,
                'accommodations' => $accommodations,
            ];
        }, $rows);
    }

    /**
     * @param  array<int, int>  $packageLabelIds
     * @return array<int, int>
     */
    private function syncOptionLabelIds(array $packageLabelIds, bool $featured, bool $recommended): array
    {
        $ids = collect($packageLabelIds);
        $featuredId = PackageLabel::query()->where('slug', 'featured')->value('id');
        $recommendedId = PackageLabel::query()
            ->whereIn('slug', ['recommended', 'recommended'])
            ->value('id');

        if ($featuredId !== null) {
            if ($featured) {
                $ids->push((int) $featuredId);
            } else {
                $ids = $ids->reject(fn (int $id): bool => $id === (int) $featuredId)->values();
            }
        }

        if ($recommendedId !== null) {
            if ($recommended) {
                $ids->push((int) $recommendedId);
            } else {
                $ids = $ids->reject(fn (int $id): bool => $id === (int) $recommendedId)->values();
            }
        }

        return $ids->unique()->values()->all();
    }
}
