<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserIdAndIdRequest extends FormRequest
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
            'id' => 'required|integer',
            'user_id' => 'nullable|integer'
        ];
    }

    public function validationData()
    {
        return $this->route()->parameters();
    }
}
