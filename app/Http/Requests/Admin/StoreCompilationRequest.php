<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreCompilationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title'=>['required', 'string', 'min:8', 'max:100',
                Rule::unique('compilations')->where(function ($query) {
                    return $query->where('created_by', Auth::id());
                })],
            'background'=>['required', 'image', 'max:10240',],
            'description'=>['required', 'string', 'max:10000'],
            'type_id'=>['required', 'exists:compilation_type,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
