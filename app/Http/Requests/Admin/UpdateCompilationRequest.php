<?php

namespace App\Http\Requests\Admin;

class UpdateCompilationRequest extends StoreCompilationRequest
{
    public function rules(): array
    {
        return [
            'background' => ['sometimes', 'image', 'max:10240',],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
