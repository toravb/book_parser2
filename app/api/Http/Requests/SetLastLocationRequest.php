<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetLastLocationRequest extends FormRequest
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
            'type' => 'required|string|in:city,zip_code,current_location',
            'name' => 'required|string|max:100',
            'point' => 'required|array',
            'point.lat' => 'required|numeric|min:-90|max:90',
            'point.lng' => 'required|numeric|min:-180|max:180'
        ];
    }
}
