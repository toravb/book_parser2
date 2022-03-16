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
            'book_id' => ['required', 'integer', 'exists:books,id'],
            'page_id' => ['required', 'integer',
                Rule::exists('pages', 'page_number')->where('book_id', $this->book_id)],
            'text' => ['required', 'string', 'max:300'],
            'color' => ['sometimes', 'string', 'max:10'],
            'start_key' => ['required', 'string', 'max:190'],
            'start_text_index' => ['required', 'integer'],
            'start_offset' => ['required', 'integer'],
            'end_key' => ['required', 'string', 'max:190'],
            'end_text_index' => ['required', 'integer'],
            'end_offset' => ['required', 'integer'],
        ];
    }
}
