<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePromotionRequest extends FormRequest
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
            'start_of_promotion' => 'required|date',
            'end_of_promotion' => 'required|date|after:start_of_promotion',
            'type' => 'required|in:post,event',
            'id' => 'required|integer',
        ];
    }
}
