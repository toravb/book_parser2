<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BooksChapterValidation extends FormRequest
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
            'id' => ['required', 'integer']
        ];
    }

    public function validationData()
    {
        return $this->route()->parameters();
    }

}
