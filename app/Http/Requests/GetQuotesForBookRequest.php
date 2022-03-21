<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetQuotesForBookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', Rule::exists(Book::class)]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function validationData()
    {
        return $this->route()->parameters;
    }
}
