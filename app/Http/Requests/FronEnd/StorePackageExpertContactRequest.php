<?php

namespace App\Http\Requests\FronEnd;

use Illuminate\Foundation\Http\FormRequest;

class StorePackageExpertContactRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'is_travel_advisor' => ['required', 'in:yes,no'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'privacy_accepted' => ['required', 'accepted'],
            'wants_newsletter' => ['nullable', 'boolean'],
            'package_title' => ['required', 'string', 'max:255'],
            'package_slug' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string|null>
     */
    public function contactPayload(): array
    {
        $validated = $this->validated();

        $noteLines = [
            'Package: '.$validated['package_title'],
        ];

        if (! empty($validated['package_slug'])) {
            $noteLines[] = 'Package slug: '.$validated['package_slug'];
        }

        $noteLines[] = 'Travel advisor: '.($validated['is_travel_advisor'] === 'yes' ? 'Yes' : 'No');
        $noteLines[] = 'Newsletter opt-in: '.($this->boolean('wants_newsletter') ? 'Yes' : 'No');

        if (! empty($validated['notes'])) {
            $noteLines[] = '';
            $noteLines[] = 'Travel plans:';
            $noteLines[] = $validated['notes'];
        }

        return [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'country_prefix' => null,
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'notes' => implode("\n", $noteLines),
        ];
    }
}
