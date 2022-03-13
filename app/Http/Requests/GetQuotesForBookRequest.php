<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetQuotesForBookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:books,id']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function validationData()
    {
        return $this->route()->parameters;
    }
}
