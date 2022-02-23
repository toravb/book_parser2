<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGenreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            //TODO: Прописать валидацию на проверку наличия ID в БД, после рефокторинга таблиц с жанрами
            'id' => ['required', 'integer'],
            'genre' => ['required', 'string', 'unique:book_genres,name' ]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
