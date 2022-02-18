<?php

namespace App\Api\Http\Requests;

use App\Api\Filters\QueryFilter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MainPageBookFilterRequest extends FormRequest
{
    public function rules()
    {

        return [
//            'findByAuthor' => ['sometimes', 'string', 'max:200'],
            'alphabetAuthorIndex' => ['sometimes', 'string', 'alpha'],
//            'findByTitle' => [
//                'sometimes',
//                'string',
//                'max:200',
//            ],
            'alphabetTitleIndex' => ['sometimes', 'string', 'alpha'],
            'findByCategory' => ['sometimes', 'integer'],
            'sortBy' => ['required', 'integer',
                Rule::in(
                    QueryFilter::SORT_BY_READERS_COUNT,
                    QueryFilter::SORT_BY_RATING_LAST_YEAR,
                    QueryFilter::SORT_BY_REVIEWS,
                    QueryFilter::BESTSELLERS)],

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
