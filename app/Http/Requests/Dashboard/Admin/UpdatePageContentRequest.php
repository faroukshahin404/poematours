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
        ];
    }

    /**
     * @return array{
     *   meta_title: string|null,
     *   meta_description: string|null,
     *   meta_keywords: array<int, string>,
     *   og_tags: array<string, mixed>
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
        ];
    }
}
