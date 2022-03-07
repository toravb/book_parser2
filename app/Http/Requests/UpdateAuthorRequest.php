<?php

namespace App\Http\Requests;

use App\Models\Author;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAuthorRequest extends StoreAuthorRequest
{
    public function rules(): array
    {
        $rules = [
            'id' => ['required', 'int', Rule::exists(Author::class)],
            'author' => ['required', 'string', 'max:120', Rule::unique(Author::class)->ignore($this->id)],
            'remove_avatar' => ['sometimes'],
        ];

        return array_merge(parent::rules(), $rules);
    }

    public function authorize(): bool
    {
        return true;
    }
}
