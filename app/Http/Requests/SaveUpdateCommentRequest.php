<?php

namespace App\Http\Requests;

use App\Api\Interfaces\Types;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUpdateCommentRequest extends FormRequest
{
    protected $types = [];
    protected $models = [];

    public function __construct(Types $types)
    {
        $this->models = $types->getCommentModelTypes();
        $this->types = array_keys($types->getCommentTypes());

    }

    public function rules(): array
    {
        if (is_string($this->type) and $this->type !== '' and $this->type !== null) {
            if (array_search($this->type, $this->types) !== false) {
                return [
                    'id' => ['required', 'integer', 'exists:' . $this->models[$this->type] . ',id'],
                    'text' => ['required', 'string']
                ];
            } else {
                return [
                    'type' => [Rule::in($this->types)],
                ];
            }
        }
        return [
            'type' => ['required', 'string'],

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
