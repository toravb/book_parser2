<?php

namespace App\AuthApi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
            'email' => 'required|email|max:255'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email обязателен',
            'email.email' => 'Email введен в неверном формате',
            'email.max' => 'Максимальная длина в поле Email 255 символов',
        ];
    }
}
