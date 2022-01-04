<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetFilesRequest extends FormRequest
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
            'type_files' => 'required|string|in:photos,videos',
            'user_id' => 'required|integer',
            'page' => 'sometimes|integer'
        ];
    }

    public function validationData()
    {
        return array_merge($this->route()->parameters(), $this->all());
    }
}
