<?php

namespace App\Api\Http\Requests;

use App\Models\Author;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetByLetterAuthorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'letterAuthor' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
