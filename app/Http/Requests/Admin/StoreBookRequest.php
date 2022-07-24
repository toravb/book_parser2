<?php

namespace App\Http\Requests\Admin;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Series;
use App\Models\Year;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'unique:books'],
            'authors_ids' => ['required', 'array'],
            'authors_ids.*' => ['required', 'int', Rule::exists(Author::class, 'id')],
            'year_id' => ['required', 'int', Rule::exists(Year::class, 'id')],
            'series_id' => ['nullable', 'int', Rule::exists(Series::class, 'id')],
            'publisher_id' => ['nullable', 'int', Rule::exists(Publisher::class, 'id')],
            'text' => ['nullable', 'string', 'max:10240'],
            'genres_id' => ['array'],
            'genres_id.*' => ['required', 'int', Rule::exists(Genre::class, 'id')],
            'active' => ['required', 'boolean'],
            'cover_image' => ['sometimes', 'nullable', 'image'],
            'file_for_parsing' => ['sometimes', 'file', 'mimetypes:application/pdf,application/epub+zip,application/zip,multipart/x-zip,application/x-zip-compressed'],
            'cover_image_remove' => ['sometimes', 'nullable', 'boolean'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'alias_url' => ['nullable', 'string', 'max:255', Rule::unique(Book::class)],
        ];
    }

    public function attributes()
    {
        return [
            'file_for_parsing' => 'Загрузить книгу'
        ];
    }

    public function messages()
    {
        return [
            'title.unique' => 'Книга с таким названием уже существует'
        ];
    }
}
