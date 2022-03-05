<?php

namespace App\Http\Requests;

use App\Models\Genre;
use Illuminate\Validation\Rule;

class UpdateGenreRequest extends StoreGenreRequest
{
    public function rules(): array
    {
        $rules = [
            'id' => ['required', 'int', Rule::exists(Genre::class)],
            'name' => ['required', 'string', Rule::unique(Genre::class)->ignore($this->id)]
        ];

        return array_merge(parent::rules(), $rules);
    }

    public function authorize(): bool
    {
        return true;
    }
}
