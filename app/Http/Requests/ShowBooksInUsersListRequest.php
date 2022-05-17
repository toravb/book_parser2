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
                    QueryFilter::BESTSELLERS,
                    QueryFilter::SORT_BY_ALPHABET,
                    QueryFilter::BY_DATE_ADDED_IN_LIST
                ]),
            ],
            'findByTitle' => ['sometimes','nullable', 'string', 'max:200']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
