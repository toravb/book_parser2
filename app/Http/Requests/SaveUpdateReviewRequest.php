<?php

namespace App\Http\Requests;

use App\Api\Interfaces\Types;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUpdateReviewRequest extends FormRequest
{
    protected $types = [];
    protected $models = [];

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
        if (is_string($this->type) and $this->type !== '' and $this->type !== null) {
            return [
                'id' => ['required', 'integer', 'exists:' . $this->models[$this->type] . ',id'],
                'type' => [Rule::in($this->types)],
                'review_type' => ['required', 'integer', 'exists:review_types,id'],
                'title' => ['required', 'string', 'max:150'],
                'text' => ['required', 'string']
            ];
        }

        return [
            'type' => ['required', 'string'],

        ];
    }
}
