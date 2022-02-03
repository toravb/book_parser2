<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetUserAuthorsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'letter' => ['sometimes', 'string', 'max:200'],
            //
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
