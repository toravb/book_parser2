<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserCompilationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer'],
            'title' => ['required', 'string', 'min:8', 'max:100'],
            'background' => ['sometimes', 'nullable', 'image', 'max:10240',],
            'description' => ['required', 'string', 'max:10000'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
