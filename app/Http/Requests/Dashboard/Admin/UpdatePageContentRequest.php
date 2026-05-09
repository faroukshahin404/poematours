<?php

namespace App\Http\Requests\Dashboard\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class UpdatePageContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('footer_sort_order') === '' || $this->input('footer_sort_order') === null) {
            $this->merge(['footer_sort_order' => null]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
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
