<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string|min:64|max:90'
        ];
    }

    public function messages()
    {
        return [
            'password.confirmed' => 'Sorry, the passwords you entered do not match',
            'password.min' => 'Your password must be at least 8 characters long'
        ];
    }
}
