<?php

namespace App\Http\Requests;

use App\Models\AudioBook;
use App\Models\Author;
use App\Models\Genre;
use App\Models\Year;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAudioBookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:64000'],
            'authors_ids' => ['required', 'array'],
            'authors_ids.*' => ['required', 'int', Rule::exists(Author::class, 'id')],
            'year_id' => ['required', 'int', Rule::exists(Year::class, 'id')],
            'genre_id' => ['required', 'int', Rule::exists(Genre::class, 'id')],
            'cover_image' => ['nullable', 'image'],
            'cover_image_remove' => ['sometimes', 'nullable', 'boolean'],
            'active' => ['required', 'boolean'],
            'meta_description' => ['nullable', 'string', 'max:191'],
            'meta_keywords' => ['nullable', 'string', 'max:191'],
            'alias_url' => ['nullable', 'string', 'max:1000', Rule::unique(AudioBook::class)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
