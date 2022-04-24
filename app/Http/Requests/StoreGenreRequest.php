<?php

namespace App\Http\Requests;

use App\Models\Genre;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGenreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', Rule::unique(Genre::class)],
            'is_hidden' => ['sometimes', 'nullable'],
            'alias' => ['string', Rule::unique(Genre::class)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
