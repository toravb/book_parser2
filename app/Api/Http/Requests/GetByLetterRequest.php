<?php

namespace App\Api\Http\Requests;

use App\Models\Author;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetByLetterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'letter' => ['required', 'string', 'size:1'],
        ];
    }

    public function validationData()
    {
        return request()->route()->parameters();
    }

    public function authorize(): bool
    {
        return true;
    }
}
