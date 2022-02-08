<?php

namespace App\Api\Http\Requests;

use App\Models\Quote;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserQuotesRequest extends FormRequest
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
                    Quote::SHOW_BY_BOOK_AND_AUTHOR,
                    Quote::SHOW_BY_BOOK,
                    Quote::SHOW_BY_AUTHOR)
            ],
        ];

    }
}

{

}
