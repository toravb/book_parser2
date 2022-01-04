<?php

namespace App\Http\Requests;

class SocialRegisterRequest extends UserRequest
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
        return array_merge(parent::rules(), [
            'token' => 'required|string|max:250|min:150',
            'email' => 'sometimes|string|email|max:255|unique:users',
            'id' => 'required|string',
        ]);
    }
}
