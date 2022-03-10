<?php

namespace App\Api\Http\Requests;

use App\Api\Interfaces\Types;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShowCommentsOnCommentRequest extends FormRequest
{
    protected $models = [];
    protected $types = [];

    protected $stopOnFirstFailure = true;

    public function __construct(Types $types)
    {
        $this->types = array_keys($types->getCommentTypes());
        $this->models = $types->getCommentTypes();
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in($this->types)],
            'id' => ['required', 'integer', Rule::exists($this->models[$this->type] ?? null)],
            'perpage' => ['required', 'integer', 'between:1,10']

        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function validationData()
    {
        return array_merge($this->route()->parameters(), $this->all());
    }
}
