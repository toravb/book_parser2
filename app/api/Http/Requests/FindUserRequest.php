<?php

namespace App\Http\Requests;

use App\Rules\CheckAccountType;
use App\Rules\CheckUserType;

class FindUserRequest extends UserRequest
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
            'account_type' => ['required_with:user_type', 'string', new CheckAccountType($this->types, 'name'),],
            'search' => ['nullable', 'string'],
            'user_type' => [
                'sometimes',
                'required',
                'string',
                new CheckUserType($this->types, $this->account_type, 'name'),
            ]
        ];
    }
}
