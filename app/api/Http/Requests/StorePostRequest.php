<?php

namespace App\Http\Requests;

use App\Interfaces\Types;
use App\Rules\ExistMediaUploaded;
use App\Rules\ExistMediaSelected;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{

    private $types;

    public function __construct(Types $types)
    {
        $this->types = $types;
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
            'text' => 'required_without_all:media_uploaded,photo_selected,video_selected|max:5000',
            'media_uploaded' => ['nullable', 'array', new ExistMediaUploaded()],
            'media_uploaded.*' => ['sometimes', 'string'],
            'photo_selected.*' => ['sometimes', 'integer'],
            'photo_selected' => ['nullable', 'array', new ExistMediaSelected('photo', $this->types)],
            'video_selected.*' => ['sometimes', 'integer'],
            'video_selected' => ['nullable', 'array', new ExistMediaSelected('video', $this->types)],
        ];
    }
}
