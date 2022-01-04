<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavePhotoRequest extends FormRequest
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
        if ($this->type !== null) {
            if ($this->type === 'video') {
                return [
                    'items' => 'required|array',
                    'items.*' => 'required|regex:/^(video)\/\w+\.(mp4)$/|max:80'
                ];
            }

            if ($this->type === 'photo') {
                return [
                    'items' => 'required|array',
                    'items.*' => 'required|regex:/^(photo)\/.{40}\.(jpeg)?(png)?(jpg)?(webp)?$/|max:65'
                ];
            }
        }
        return [
            'type' => 'required| in:video,photo',
            'items' => 'required|array',
            'items.*' => 'required|regex:/^(photo)\/.{40}\.(jpeg)?(png)?(jpg)?(webp)?$/|max:65'
        ];
    }

    public function validationData()
    {
        return array_merge($this->route()->parameters(), $this->all());
    }
}
