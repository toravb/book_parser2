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
                    QueryFilter::SORT_BY_DATE,
                    QueryFilter::BESTSELLERS,
                    QueryFilter::SORT_BY_ALPHABET
                ]),
            ]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
