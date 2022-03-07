<?php

namespace App\Http\Requests;

use App\Models\Author;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAuthorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'author' => ['required', 'string', 'max:120', Rule::unique(Author::class)],
            'avatar' => ['nullable', 'image'],
            'about' => ['nullable', 'string', 'max:191'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
