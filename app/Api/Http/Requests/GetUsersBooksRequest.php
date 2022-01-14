<?php

namespace App\Api\Http\Requests;

use App\api\Http\Controllers\UsersBooksController;
use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Api\Http\Controllers\BookController;

class GetUsersBooksRequest extends FormRequest
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
            'search' => ['sometimes', 'string', 'max:200'],
            'sortBy' => ['required', 'integer',
                Rule::in(
                    Book::SORT_BY_DATE,
                    Book::SORT_BY_RATING,
                    Book::SORT_BY_ALPHABET)],
            'status' => ['required', 'integer',

                    Rule::in(
                        Book::WANT_READ,
                        Book::READING,
                        Book::HAD_READ)],

        ];
    }
}
