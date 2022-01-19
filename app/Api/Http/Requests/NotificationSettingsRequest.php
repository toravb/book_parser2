<?php

namespace App\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationSettingsRequest extends FormRequest
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
            'likes'=>['required','boolean'],
            'commented'=>['required','boolean'],
            'commentedOthers'=>['required', 'boolean']
        ];
    }
}
