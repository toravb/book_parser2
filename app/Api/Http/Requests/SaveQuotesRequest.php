<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveQuotesRequest extends FormRequest
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
            'bookId' => ['required', 'integer', 'exists:books,id'],
            'pageId' => ['required', 'integer',
                Rule::exists('pages', 'page_number')->where('book_id', $this->bookId)],
            'text' => ['required', 'string', 'max:300'],
            'color' => ['sometimes', 'string', 'max:10'],
            'position' => ['required', 'integer', 'min:0'],
        ];
    }
}
