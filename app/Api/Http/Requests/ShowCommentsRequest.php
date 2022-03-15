<?php

namespace App\Api\Http\Requests;

use App\Api\Interfaces\Types;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShowCommentsRequest extends FormRequest
{
    protected $types = [];
    protected $models = [];

    protected $stopOnFirstFailure = true;

    public function __construct(Types $types)
    {
        $this->models = $types->getCommentModelTypes();
        $this->types = array_keys($types->getCommentTypes());

        parent::__construct();
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in($this->types)],
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
