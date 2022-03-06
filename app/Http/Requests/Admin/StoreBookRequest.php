<?php

namespace App\Http\Requests\Admin;

use App\Models\Author;
use App\Models\Genre;
use App\Models\Year;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'author_id' => ['required', 'int', Rule::exists(Author::class, 'id')],
            'year_id' => ['required', 'int', Rule::exists(Year::class, 'id')],
            'text' => ['nullable', 'string', 'max:10240'],
            'genres_id' => ['array'],
            'genres_id.*' => ['required', 'int', Rule::exists(Genre::class, 'id')],
            'active' => ['required', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
