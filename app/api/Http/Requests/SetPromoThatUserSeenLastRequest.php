<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetPromoThatUserSeenLastRequest extends FormRequest
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
            'type' => 'required|in:post,event',
            'promo_id' => 'required|integer|exists:promotions,id'
        ];
    }
}
