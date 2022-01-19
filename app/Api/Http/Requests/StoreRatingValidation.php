<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreRatingValidation extends FormRequest
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
            'book_id' => ['required', 'integer', 'exists:books,id',
                Rule::unique('rates')->where(function ($query) {
                return $query->where('user_id', Auth::id());
            })],
            'rating' => ['required', 'numeric', 'between:1,5'],


        ];
    }
}
