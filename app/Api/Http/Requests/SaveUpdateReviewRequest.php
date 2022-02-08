<?php

namespace App\Api\Http\Requests;

use App\Api\Interfaces\Types;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUpdateReviewRequest extends FormRequest
{
    protected $types = [];
    protected $models = [];

    protected $stopOnFirstFailure = true;

    public function __construct(Types $types)
    {
        $this->models = $types->getReviewModelTypes();
        $this->types = array_keys($types->getReviewTypes());
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
            'type' => ['required', 'string', Rule::in($this->types)],
            'id' => ['required', 'integer', Rule::exists($this->models[$this->type]??null . '_id')],
            'review_type' => ['required', 'integer', 'exists:review_types,id'],
            'title' => ['required', 'string', 'max:150'],
            'text' => ['required', 'string']
        ];
    }
}
