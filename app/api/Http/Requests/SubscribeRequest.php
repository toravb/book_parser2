<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SubscribeRequest extends FormRequest
{
    public $id;

    public function __construct()
    {
        $this->id = Auth::id();
    }

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
            'user_id' => ['bail', 'required', 'integer', Rule::notIn([$this->id]), 'exists:users,id']
        ];
    }

    public function messages()
    {
        return [
            'user_id.not_in' => 'You can not follow yourself',
        ];
    }
}
