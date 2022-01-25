<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CurrentReadingRequest extends FormRequest
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
            'id' => ['required', 'integer', 'exists:pages,book_id'],
            'pageNumber' => [
                'sometimes',
                'numeric',
                Rule::exists('pages', 'page_number')->where('book_id', $this->route()->parameter('id')),
            ]
        ];
    }

    public function validationData()
    {
        return array_merge($this->route()->parameters(), $this->all());
    }

}
