<?php

namespace App\Http\Requests;

use App\Api\Interfaces\Types;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteReviewRequest extends FormRequest
{
    protected $types = [];

    public function __construct(Types $types)
    {
        $this->types = array_keys($types->getReviewTypes());
    }
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

    public function authorize(): bool
    {
        return true;
    }
}
