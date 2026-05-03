<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\PageSection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class UpdatePageSectionContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'content' => ['required', 'array'],
            'uploads' => ['nullable', 'array'],
            'uploads.*' => ['nullable', 'file', 'image', 'max:5120'],
        ];
    }

    /**
     * @return array{order: int, is_active: bool, content: array<string, mixed>}
     */
    public function sectionPayload(PageSection $section): array
    {
        $validated = $this->validated();
        $content = is_array($validated['content'] ?? null) ? $validated['content'] : [];

        $uploads = $this->file('uploads', []);
        if (is_array($uploads)) {
            foreach ($uploads as $fieldKey => $file) {
                if (! ($file instanceof UploadedFile)) {
                    continue;
                }

                $token = (string) $fieldKey;
                if (str_contains($token, '__')) {
                    [$collectionKey, $index, $childKey] = array_pad(explode('__', $token, 3), 3, null);
                    if (
                        $collectionKey !== null && $index !== null && $childKey !== null &&
                        isset($content[$collectionKey]) && is_array($content[$collectionKey]) &&
                        isset($content[$collectionKey][(int) $index]) && is_array($content[$collectionKey][(int) $index])
                    ) {
                        $content[$collectionKey][(int) $index][$childKey] = 'storage/'.$file->store('page-content', 'public');
                    }
                    continue;
                }

                $content[$token] = 'storage/'.$file->store('page-content', 'public');
            }
        }

        return [
            'order' => (int) $validated['order'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
            'content' => $content,
        ];
    }
}
