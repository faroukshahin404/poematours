<?php

namespace App\Http\Requests\Dashboard\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePackageReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'reviewer_name' => ['required', 'string', 'max:255'],
            'reviewer_address' => ['nullable', 'string', 'max:255'],
            'comment' => ['required', 'string'],
            'rate' => ['required', 'integer', 'min:1', 'max:5'],
        ];
    }

    /**
     * @return array{reviewer_name: string, reviewer_address: ?string, comment: string, rate: int}
     */
    public function reviewPayload(): array
    {
        $validated = $this->validated();

        $addr = isset($validated['reviewer_address']) ? trim((string) $validated['reviewer_address']) : '';

        return [
            'reviewer_name' => trim((string) $validated['reviewer_name']),
            'reviewer_address' => $addr === '' ? null : $addr,
            'comment' => trim((string) $validated['comment']),
            'rate' => (int) $validated['rate'],
        ];
    }
}
