<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' =>'required|min:4|string|max:255',
            'surname' =>'required|min:4|string|max:255',
            'email'=>'required|email|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }


}
