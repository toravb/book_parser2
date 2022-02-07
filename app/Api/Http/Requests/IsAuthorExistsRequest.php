<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IsAuthorExistsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:authors']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function validationData(): ?array
    {
        return $this->route()->parameters();
    }
}
