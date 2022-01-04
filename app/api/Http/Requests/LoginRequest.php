<?php

namespace App\api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        return [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'password.min' => 'Извините, вы ввели неверный адрес электронной почты или пароль.
            Пожалуйста, введите правильный или воспользуйтесь восстановлением пароля.',
            'password.required' => 'Извините, вы ввели неверный адрес электронной почты или пароль.
            Пожалуйста, введите правильный или воспользуйтесь восстановлением пароля.',
            'password.string' => 'Извините, вы ввели неверный адрес электронной почты или пароль.
            Пожалуйста, введите правильный или воспользуйтесь восстановлением пароля.',
            'email.required' => 'Извините, вы ввели неверный адрес электронной почты или пароль.
            Пожалуйста, введите правильный или воспользуйтесь восстановлением пароля.',
            'email.email' => 'Извините, вы ввели неверный адрес электронной почты или пароль.
            Пожалуйста, введите правильный или воспользуйтесь восстановлением пароля.',
            'email.max' => 'Извините, вы ввели неверный адрес электронной почты или пароль.
            Пожалуйста, введите правильный или воспользуйтесь восстановлением пароля.',
        ];
    }
}
