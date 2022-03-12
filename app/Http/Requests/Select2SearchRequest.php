<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Select2SearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'nullable', 'string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
