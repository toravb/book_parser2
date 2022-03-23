<?php

namespace App\Http\Requests\Admin;

use App\Models\Genre;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBannerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'is_active' => ['required', 'boolean'],
            'name' => ['required', 'string', 'max:1000'],
            'image' => ['sometimes', 'nullable', 'image'],
            'image_remove' => ['sometimes', 'nullable', 'boolean'],
            'text' => ['nullable', 'string', 'max:2000'],
            'link' => ['required', 'string', 'max:3500'],
            'content' => ['nullable', 'string'],
            'genres_id' => ['array'],
            'genres_id.*' => ['required', 'int', Rule::exists(Genre::class, 'id')]

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
