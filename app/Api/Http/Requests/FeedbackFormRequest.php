<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'subject' => ['required', 'string'],
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'message' => ['nullable', 'string', 'max:64000'],
            'attachments' => ['sometimes', 'array'],
            'attachments.*' => ['required', 'file']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
