<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestroyCompilationUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
