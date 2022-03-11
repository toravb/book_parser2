<?php

namespace App\Api\Http\Requests;

use App\Api\Interfaces\Types;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetReviewRequest extends FormRequest
{
    protected $types = [];
    protected $models = [];

    protected $stopOnFirstFailure = true;

    public function __construct(Types $types)
    {
        $this->models = $types->getReviewModelTypes();
        $this->types = array_keys($types->getReviewTypes());
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in($this->types)],
            'id' => ['required', 'integer', Rule::exists($this->models[$this->type] ?? null)],
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
