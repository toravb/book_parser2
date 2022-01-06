<?php

namespace App\AuthApi\Http\Requests;

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
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email обязателен',
            'email.email' => 'Email введен в неверном формате',
            'email.max' => 'Максимальная длина в поле Email 255 символов',
            'token.required' => 'Ссылка из письма неверная',
            'token.string' => 'Ссылка из письма неверная',
            'password.required' => 'Пароль обязателен',
            'password.string' => 'Пароль должен быть строкой',
            'password.min' => 'Минимальная длина пароля должна быть 6 символов',
            'password.confirmed' => 'Пароли не совпадают',
            'password_confirmation.required' => 'Повтор пароля обязателен',
            'password_confirmation.string' => 'Пароль должен быть строкой',
            'password_confirmation.min' => 'Минимальная длина в поле подтвеждения пароля должна быть 6 символов',
        ];
    }
}
