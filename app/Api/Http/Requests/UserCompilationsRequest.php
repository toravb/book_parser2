<?php

namespace App\Api\Http\Requests;

use App\Models\Compilation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCompilationsRequest extends FormRequest
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
            'letter'=>['sometimes', 'string'],
            'compType'=>['required', 'integer',
                Rule::in(
                    Compilation::COMPILATION_USER,
                    Compilation::COMPILATION_ADMIN,
                    Compilation::COMPILATION_ALL)
            ],
            'sortBy' => ['required', 'integer',
                Rule::in(
                    Compilation::SORT_BY_DATE,
                    Compilation::SORT_BY_ALPHABET)
                  ],
        ];


    }
}
