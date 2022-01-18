<?php

namespace App\Api\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Api\Http\Controllers\BookController;

class GetBooksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'showType' => ['required', Rule::in([Book::SHOW_TYPE_BLOCK, Book::SHOW_TYPE_LIST])],
            'findByAuthor' => ['sometimes', 'string', 'max:200'],
            'findByPublisher' => ['sometimes', 'string', 'max:200'],
            'findByTitle' => ['sometimes', 'string', 'max:200'],
            'sortBy' => ['required', 'integer',
                Rule::in(
                    Book::SORT_BY_DATE,
                    Book::SORT_BY_RATING,
                    Book::SORT_BY_READERS_COUNT,
                    Book::SORT_BY_ALPHABET)],

        ];
    }
}
