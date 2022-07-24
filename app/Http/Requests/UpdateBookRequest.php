<?php

namespace App\Http\Requests;

use App\Http\Requests\Admin\StoreBookRequest;
use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends StoreBookRequest
{
    public function rules(): array
    {
        $rules = [
            'id' => ['required', 'int', Rule::exists(Book::class)],
            'title' => ['required', 'string', Rule::unique(Book::class)->ignore($this->id)],
            'alias_url' => ['nullable', 'string', 'max:255', Rule::unique(Book::class)->ignore($this->id)],
        ];

        return array_merge(parent::rules(), $rules);
    }

    public function authorize(): bool
    {
        return true;
    }
}
