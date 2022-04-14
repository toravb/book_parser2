<?php

namespace App\Api\Http\Requests;

use App\Api\Interfaces\Types;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveCommentRequest extends FormRequest
{
    protected $types = [];
    protected $models = [];
    protected $modelOfComment = [];

    protected $stopOnFirstFailure = true;

    public function __construct(Types $types)
    {
        $this->models = $types->getCommentModelTypes();
        $this->types = array_keys($types->getCommentTypes());
        $this->modelOfComment = $types->getCommentTypes();

        parent::__construct();
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in($this->types)],
            'id' => ['required', 'integer', Rule::exists($this->models[$this->type] ?? null . '_id')],
            'parent_comment_id' => ['sometimes', 'nullable', 'integer',  Rule::exists($this->modelOfComment[$this->type] ?? null, 'id' )],
            'text' => ['required', 'string', 'max:65500' ]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
