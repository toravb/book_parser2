<?php

namespace App\api\Http\Requests;



class RegistrationRequest extends UserRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return  [

            'email' => ['required', 'email', 'max:255', 'unique:users'],

            'password' => ['required', 'string', 'min:8'],
        ];
    }


}
