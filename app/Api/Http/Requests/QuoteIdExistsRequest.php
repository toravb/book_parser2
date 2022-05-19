<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteIdExistsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id' => ['required', 'integer', 'exists:quotes'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function validationData()
    {
        return $this->route()->parameters();
    }
}
