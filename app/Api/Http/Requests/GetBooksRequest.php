<?php

namespace App\Api\Http\Requests;

use App\Api\Services\TypesGenerator;
use App\Models\AudioBook;
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
            'alphabetAuthorIndex' => ['sometimes', 'string', 'alpha'],
            'findByPublisher' => ['sometimes', 'string', 'max:200'],
            'alphabetPublisherIndex' => ['sometimes', 'string', 'alpha'],
            'findByTitle' => ['sometimes', 'string', 'max:200',],
            'alphabetTitleIndex' => ['sometimes', 'string', 'alpha'],
            'findByCategory' => ['sometimes', 'integer'],
            'type' => ['required', 'string', Rule::in(array_keys((new TypesGenerator())->getCompilationsBookTypes()))],
            'sortBy' => ['required', 'integer',
                Rule::in(
                    Book::SORT_BY_DATE,
                    Book::SORT_BY_RATING,
                    Book::SORT_BY_READERS_COUNT,
                    Book::SORT_BY_ALPHABET)],
            'bookType' => ['sometimes', 'string',
                Rule::in(
                    Book::TYPE_BOOK,
                    AudioBook::TYPE_AUDIO_BOOK)],

        ];
    }
}
