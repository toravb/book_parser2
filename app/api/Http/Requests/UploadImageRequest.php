<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{

    //Max size of uploaded image in Kb
    public $size;


    public function __construct()
    {
        $this->size = config("filesystems.max_image_size") * 1024;
    }

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
            'path' => 'required|in:photo',
            'file' => [
                'required',
                'file',
                'mimes:jpeg,jpg,png,webp|dimensions:max_width=1200,max_height=1200',
                'max:' . $this->size,
            ]
        ];
    }
}
