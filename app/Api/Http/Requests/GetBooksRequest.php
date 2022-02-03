<?php

namespace App\Api\Http\Requests;

use App\Api\Filters\QueryFilter;
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
        if (!isset($this->type)) $this->type = QueryFilter::TYPE_BOOK;

        return [
            'showType' => ['required', Rule::in([QueryFilter::SHOW_TYPE_BLOCK, QueryFilter::SHOW_TYPE_LIST])],
            'findByAuthor' => ['sometimes', 'string', 'max:200'],
            'alphabetAuthorIndex' => ['sometimes', 'string', 'alpha'],
            'findByPublisher' => [
                'sometimes',
                'string',
                'max:200',
                'prohibited_if:type,' . QueryFilter::TYPE_AUDIO_BOOK
            ],
            'alphabetPublisherIndex' => [
                'sometimes',
                'string',
                'alpha',
                'prohibited_if:type,' . QueryFilter::TYPE_AUDIO_BOOK
            ],
            'findBySpeaker' => [
                'sometimes',
                'string',
                'max:200',
                'prohibited_if:type,' . QueryFilter::TYPE_BOOK
            ],
            'alphabetSpeakerIndex' => [
                'sometimes',
                'string',
                'alpha',
                'prohibited_if:type,' . QueryFilter::TYPE_BOOK
            ],
            'findByTitle' => [
                'sometimes',
                'string',
                'max:200',
            ],
            'alphabetTitleIndex' => ['sometimes', 'string', 'alpha'],
            'findByCategory' => ['sometimes', 'integer'],
            'type' => ['sometimes', 'string', Rule::in(array_keys((new TypesGenerator())->getCompilationsBookTypes()))],
            'sortBy' => ['required', 'integer',
                Rule::in(
                    QueryFilter::SORT_BY_DATE,
                    QueryFilter::SORT_BY_READERS_COUNT,
                    QueryFilter::SORT_BY_RATING_LAST_YEAR,
                    QueryFilter::SORT_BY_REVIEWS,
                    QueryFilter::BESTSELLERS)],
            'bookType' => ['sometimes', 'string',
                Rule::in(
                    QueryFilter::TYPE_BOOK,
                    QueryFilter::TYPE_AUDIO_BOOK)],

        ];
    }
}
