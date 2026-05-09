<?php

namespace App\Http\Requests\Dashboard\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StorePageRequest extends FormRequest
{
    /**
     * Reserved URL slugs that cannot be used for CMS pages (routes or system paths).
     *
     * @var array<int, string>
     */
    private const RESERVED_SLUGS = [
        'admin',
        'api',
        'storage',
        'vendor',
        'pages',
        'sitemap',
        'home',
        'about-us',
        'destinations',
        'our-journeys',
        'terms-and-conditions',
        'privacy-policy',
        'login',
        'register',
        'forgot-password',
        'packages',
        'search',
        'customize',
        'reservation',
        'payment',
        'contact-leads',
        'activities',
    ];

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $slug = $this->input('slug');
        if ((! is_string($slug) || trim($slug) === '') && is_string($this->input('name')) && trim((string) $this->input('name')) !== '') {
            $this->merge(['slug' => Str::slug((string) $this->input('name'))]);
        }
        if ($this->input('footer_sort_order') === '' || $this->input('footer_sort_order') === null) {
            $this->merge(['footer_sort_order' => null]);
        }
    }

    /**
     * @return array<string, array<int, string|Unique>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::notIn(self::RESERVED_SLUGS),
                'unique:pages,slug',
            ],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string'],
            'og_type' => ['nullable', 'string', 'in:website,article'],
            'og_url' => ['nullable', 'string', 'max:2048'],
            'og_image' => ['nullable', 'string', 'max:2048'],
            'og_image_file' => ['nullable', 'file', 'image', 'max:5120'],
            'body' => ['nullable', 'string'],
            'show_in_footer' => ['sometimes', 'boolean'],
            'footer_label' => ['nullable', 'string', 'max:255'],
            'footer_sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ];
    }

    /**
     * @return array{
     *   name: string,
     *   slug: string,
     *   meta_title: string|null,
     *   meta_description: string|null,
     *   meta_keywords: array<int, string>,
     *   og_tags: array<string, mixed>,
     *   body: string|null,
     *   show_in_footer: bool,
     *   footer_label: string|null,
     *   footer_sort_order: int|null
     * }
     */
    public function pagePayload(): array
    {
        $validated = $this->validated();

        $normalizedKeywords = collect(explode(',', (string) ($validated['meta_keywords'] ?? '')))
            ->map(fn ($value): string => trim((string) $value))
            ->filter(fn (string $value): bool => $value !== '')
            ->values()
            ->all();

        $ogTags = collect([
            'title' => $validated['og_title'] ?? null,
            'description' => $validated['og_description'] ?? null,
            'type' => $validated['og_type'] ?? null,
            'url' => $validated['og_url'] ?? null,
            'image' => $validated['og_image'] ?? null,
        ])->filter(fn ($value): bool => ! is_null($value) && trim((string) $value) !== '')->all();

        $ogImageFile = $this->file('og_image_file');
        if ($ogImageFile instanceof UploadedFile) {
            $ogTags['image'] = 'storage/'.$ogImageFile->store('page-content', 'public');
        }

        return [
            'name' => (string) $validated['name'],
            'slug' => (string) $validated['slug'],
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $normalizedKeywords,
            'og_tags' => $ogTags,
            'body' => isset($validated['body']) && trim((string) $validated['body']) !== ''
                ? (string) $validated['body']
                : null,
            'show_in_footer' => $this->boolean('show_in_footer'),
            'footer_label' => isset($validated['footer_label']) && trim((string) $validated['footer_label']) !== ''
                ? (string) $validated['footer_label']
                : null,
            'footer_sort_order' => isset($validated['footer_sort_order'])
                ? (int) $validated['footer_sort_order']
                : null,
        ];
    }
}
