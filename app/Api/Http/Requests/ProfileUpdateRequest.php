<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255', 'unique:App\Models\User'],

        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email обязателен',
            'email.email' => 'Email должен быть в формате user@email.com',
            'email.max' => 'Максимальное количество символов в поле Email 255',
            'email.unique' => 'Такой Email уже зарегистрирован в системе',
            'password.required' => 'Пароль обязателен',
            'password.string' => 'Пароль должен быть строкой',
            'password.min' => 'Минимальное количество символов в поле пароль 6',
        ];
    }
}
