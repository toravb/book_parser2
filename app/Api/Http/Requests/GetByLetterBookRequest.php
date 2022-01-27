<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetByLetterBookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'letterBook' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
