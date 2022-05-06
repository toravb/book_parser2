<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AddCompilationToFavoriteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'compilation_id' => [
                'required',
                'integer',
                'exists:compilations,id',
                Rule::unique('compilation_user')->where(function ($q) {
                    $q->where('user_id', Auth::id());
                })]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
