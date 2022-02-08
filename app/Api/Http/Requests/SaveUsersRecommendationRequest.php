<?php

namespace App\Api\Http\Requests;

use App\Api\Interfaces\Types;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUsersRecommendationRequest extends FormRequest
{
    protected $types = [];
    protected $models = [];

    protected $stopOnFirstFailure = true;

    public function __construct(Types $types)
    {
        $this->models = $types->getRecommendModelTypes();
        $this->types = array_keys($types->getRecommendTypes());
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in($this->types)],
            'id' => ['required', 'integer', Rule::exists($this->models[$this->type]??null . '_id')],
            'content' => ['required', 'string']
        ];
    }
}
