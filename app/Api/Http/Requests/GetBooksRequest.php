<?php

namespace App\Api\Http\Requests;

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
            'showType' => ['required', Rule::in([BookController::SHOW_TYPE_BLOCK, BookController::SHOW_TYPE_LIST])],
            'findByAuthor' => ['sometimes', 'string', 'max:200'],
            'findByPublisher' => ['sometimes', 'string', 'max:200'],
            'findByTitle' => ['sometimes', 'string', 'max:200'],
            'sortBy' => ['required', 'integer',
                Rule::in(
                    BookController::SORT_BY_DATE,
                    BookController::SORT_BY_RATING,
                    BookController::SORT_BY_READERS_COUNT)],

        ];
    }
}
