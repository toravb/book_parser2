<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreCompilationRequest extends FormRequest
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
            'title'=>['required', 'string', 'min:8', 'max:255',
                Rule::unique('compilations')->where(function ($query) {
                    return $query->where('created_by', Auth::id());
                })],
            'image'=>['required', 'image', 'max:10240',],
            'description'=>['required', 'string', 'max:10000'],
            'compType'=>['sometimes', 'exists:users,is_admin'],

        ];
    }
}
