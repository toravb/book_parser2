<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorsFilteringByLetterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'letter' => ['sometimes', 'string', 'alpha']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
