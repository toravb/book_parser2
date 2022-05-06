<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RemoveCompilationFromFavoriteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'compilation_id' => [
                'required',
                'integer',
                Rule::exists('compilation_user')
                    ->where(function ($q) {
                        return $q->where('user_id', Auth::id());
                    })
            ]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
