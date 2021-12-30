<?php

namespace App\AuthApi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6']
        ];
    }

    public function messages()
    {
        return [
            'password.min' => 'Минимальное количество символов пароля 6.',
            'password.required' => 'Пароль не может быть пустым.',
            'password.string' => 'Неверный пароль.
             Пожалуйста введите верные данные или восстановите пароль.',
            'email.required' => 'Email не может быть пустым.',
            'email.email' => 'Неверный email.',
            'email.max' => 'Максимальное количеситво символов 255',
        ];
    }

}
