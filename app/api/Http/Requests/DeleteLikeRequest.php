<?php

namespace App\Http\Requests;

use App\Interfaces\Types;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteLikeRequest extends FormRequest
{
    protected $types = [];

    public function __construct(Types $types)
    {
        $this->types = array_keys($types->getLikeTypes());
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
            'type' => ['bail', 'required', 'string', Rule::in($this->types)],
            'id' => ['bail', 'required', 'integer'],
        ];
    }

    public function validationData()
    {
        return array_merge($this->route()->parameters(), $this->all());
    }
}
