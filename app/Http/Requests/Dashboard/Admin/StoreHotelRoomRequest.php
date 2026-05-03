<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\Hotel;
use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StoreHotelRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $default = Language::defaultSlug();
        $names = $this->input('name', []);
        if (! $this->filled('slug')) {
            $this->merge([
                'slug' => Str::slug((string) ($names[$default] ?? 'room')),
            ]);
        }

        /** @var Hotel|null $hotel */
        $hotel = $this->route('hotel');
        if ($hotel !== null && ! $this->filled('hotel_id')) {
            $this->merge([
                'hotel_id' => $hotel->id,
            ]);
        }
    }

    /**
     * @return array<string, array<int, Unique|string>|string>
     */
    public function rules(): array
    {
        $default = Language::defaultSlug();
        $hotelId = (int) $this->input('hotel_id');
        $rules = [
            'hotel_id' => ['required', 'integer', 'exists:hotels,id'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('hotel_rooms', 'slug')->where(
                    fn ($query) => $query->where('hotel_id', $hotelId)
                ),
            ],
            'name' => ['required', 'array'],
            'name.'.$default => ['required', 'string', 'max:500'],
            'capacity' => ['required', 'integer', 'min:1'],
            'area' => ['nullable', 'numeric', 'min:0'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'single_supplement' => ['nullable', 'numeric', 'min:0'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120'],
        ];

        foreach (Language::query()->pluck('slug') as $slug) {
            if ((string) $slug === $default) {
                continue;
            }
            $rules['name.'.(string) $slug] = ['nullable', 'string', 'max:500'];
        }

        return $rules;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $allowed = Language::query()->pluck('slug')->map(fn ($s) => (string) $s)->all();
            foreach (array_keys($this->input('name', [])) as $key) {
                if (! in_array((string) $key, $allowed, true)) {
                    $validator->errors()->add('name', __('Invalid language key in name fields.'));

                    return;
                }
            }
        });
    }

    /**
     * @return array{slug: string, hotel_id: int, name: array<string, string>, capacity: int, area: float|null, base_price: float, single_supplement: float}
     */
    public function roomPayload(): array
    {
        $validated = $this->validated();
        $allowed = Language::query()->pluck('slug')->map(fn ($s) => (string) $s)->flip()->all();
        /** @var array<string, string> $name */
        $name = array_intersect_key($validated['name'], $allowed);
        $name = array_map(fn (mixed $v): string => trim((string) $v), $name);

        return [
            'slug' => (string) ($validated['slug'] ?? ''),
            'hotel_id' => (int) $validated['hotel_id'],
            'name' => $name,
            'capacity' => (int) $validated['capacity'],
            'area' => isset($validated['area']) ? (float) $validated['area'] : null,
            'base_price' => (float) $validated['base_price'],
            'single_supplement' => isset($validated['single_supplement']) ? (float) $validated['single_supplement'] : 0.0,
        ];
    }
}
