<?php

namespace App\Http\Requests;

use App\Api\Filters\QueryFilter;
use App\Models\AudioBook;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShowAudioBooksUserHasRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'nullable',
                'integer',
                Rule::in(AudioBook::$availableListeningStatuses)
            ],
            'sortBy' => [
                'required',
                'integer',
                Rule::in([
                    QueryFilter::BY_DATE_ADDED_IN_LIST,
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
