<?php

namespace App\Http\Requests;

use App\Interfaces\PolymorphTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetFavoritesRequest extends FormRequest
{

    protected $types = [];

    public function __construct(PolymorphTypes $polymorphTypes)
    {
        $this->types = array_keys($polymorphTypes->getTypes());
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
            'byDate' => ['bail', 'nullable', 'string', 'date_format:Y-m-d',],
            'type' => ['nullable', 'string', Rule::in($this->types)]
        ];
    }
}
