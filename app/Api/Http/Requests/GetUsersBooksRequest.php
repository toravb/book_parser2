<?php

namespace App\Api\Http\Requests;

use App\api\Http\Controllers\UsersBooksController;
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

            'sortBy' => ['required', 'integer',
                Rule::in(
                    UsersBooksController::SORT_BY_DATE,
                    UsersBooksController::SORT_BY_RATING,
                    UsersBooksController::SORT_BY_ALPHABET)],
            'status' => ['required', 'integer',

                    Rule::in(
                        BookController::WANT_READ,
                        BookController::READING,
                        BookController::HAD_READ)],

        ];
    }
}
