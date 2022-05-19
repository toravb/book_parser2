<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowQuotesRequest extends FormRequest
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
            'bookId' => ['required', 'numeric', 'exists:quotes,book_id'],
            'myQuotes' => ['required', 'boolean'],
            'search' => ['sometimes', 'string']
        ];
    }
}
