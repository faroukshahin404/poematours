<?php

namespace App\Http\Requests\Dashboard\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGeneralSettingsRequest extends FormRequest
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
            'notification_emails' => ['required', 'array', 'min:1'],
            'notification_emails.*' => ['required', 'email:rfc,dns', 'distinct', 'max:255'],
        ];
    }

    /**
     * @return array{notification_emails: array<int, string>}
     */
    public function settingsPayload(): array
    {
        /** @var array{notification_emails: array<int, string>} $validated */
        $validated = $this->validated();

        return [
            'notification_emails' => $validated['notification_emails'],
        ];
    }
}
