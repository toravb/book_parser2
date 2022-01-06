<?php

namespace App\AuthApi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailRequest extends FormRequest
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
            'token' => ['required', 'string', 'min:20']
        ];
    }

    public function messages()
    {
        return [
            'token.min' => 'Минимальное количество символов токена 20.',
            'token.required' => 'Токен не может быть пустым.',
            'token.string' => 'Неверный токен.',
            'email.required' => 'Email не может быть пустым.',
            'email.email' => 'Неверный email.',
            'email.max' => 'Максимальное количеситво символов 255',
        ];
    }
}
