<?php

namespace App\Http\Requests;

use App\Api\Filters\QueryFilter;
use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShowBooksInUsersListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'nullable',
                'integer',
                Rule::in(Book::$availableReadingStatuses)
            ],
            'sortBy' => [
                'required',
                'integer',
                Rule::in([
                    QueryFilter::SORT_BY_DATE,
                    QueryFilter::BESTSELLERS,
                    QueryFilter::SORT_BY_ALPHABET
                ]),
            ],
            'findByTitle' => ['sometimes', 'string', 'max:200']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
