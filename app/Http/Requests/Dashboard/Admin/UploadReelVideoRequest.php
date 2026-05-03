<?php

namespace App\Http\Requests\Dashboard\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UploadReelVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'video' => ['required', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm', 'max:51200'],
        ];
    }
}
