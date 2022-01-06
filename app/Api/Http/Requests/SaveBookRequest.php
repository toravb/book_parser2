<?php

namespace App\Api\Http\Requests;

use App\Api\Http\Controllers\BookController;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveBookRequest extends FormRequest
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
            'book_id' => ['required', 'integer', 'exists:books,id'],
            'status' => ['required', 'integer',
                Rule::in(
                    BookController::SORT_BY_DATE,
                    BookController::SORT_BY_RATING,
                    BookController::SORT_BY_READERS_COUNT)],

        ];
    }
}
