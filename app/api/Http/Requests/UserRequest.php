<?php

namespace App\api\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{

     /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [


            'name' => ['required', 'string', 'max:50'],
            'location' => ['sometimes', 'array'],
            'location.lat' => ['required_with:location', 'numeric', 'min:-90', 'max:90'],
            'location.lng' => ['required_with:location', 'numeric', 'min:-180', 'max:180']
        ];
    }



    public function messages(): array
    {
        return [
            'zip_code.required' => 'Please fill in all fields',
            'name.required' => 'Please fill in all fields',
            'user_sub_type.required_if' => 'Please fill in all fields',
        ];
    }
}
