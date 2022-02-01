<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetUserAuthorsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
